<?php



namespace pocketmine\scheduler;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginException;
use pocketmine\Server;
use pocketmine\utils\ReversePriorityQueue;

class ServerScheduler {
	public static $WORKERS = 2;
	/**
	 * @var ReversePriorityQueue<Task>
	 */
	protected $queue;

	/**
	 * @var TaskHandler[]
	 */
	protected $tasks = [];

	/** @var AsyncPool */
	protected $asyncPool;

	/** @var int */
	private $ids = 1;

	/** @var int */
	protected $currentTick = 0;

	/**
	 * ServerScheduler constructor.
	 */
	public function __construct(){
		$this->queue = new ReversePriorityQueue();
		$this->asyncPool = new AsyncPool(Server::getInstance(), self::$WORKERS);
	}

	/**
	 * @param Task $task
	 *
	 * @return null|TaskHandler
	 */
	public function scheduleTask(Task $task){
		return $this->addTask($task, -1, -1);
	}

    /**
     * Submits an asynchronous task to the worker pool
     *
     * @param AsyncTask $task
     *
     * @return int
     */
    public function scheduleAsyncTask(AsyncTask $task) : int{
        if($task->getTaskId() !== null){
            throw new \UnexpectedValueException("Attempting to schedule the same AsyncTask instance twice");
        }
        $id = $this->nextId();
        $task->setTaskId($id);
        $task->progressUpdates = new \Threaded;
        return $this->asyncPool->submitTask($task);
    }

    /**
     * Sends an asynchronous task to a specific worker in the pool.
     *
     * @param AsyncTask $task
     * @param int       $worker
     *
     * @return void
     */
    public function scheduleAsyncTaskToWorker(AsyncTask $task, $worker){
        if($task->getTaskId() !== null){
            throw new \UnexpectedValueException("Attempting to schedule the same AsyncTask instance twice");
        }
        $id = $this->nextId();
        $task->setTaskId($id);
        $task->progressUpdates = new \Threaded;
        $this->asyncPool->submitTaskToWorker($task, $worker);
    }

    /**
	 * @return int
	 */
	public function getAsyncTaskPoolSize(){
		return $this->asyncPool->getSize();
	}

	/**
	 * @param $newSize
	 */
	public function increaseAsyncTaskPoolSize($newSize){
		$this->asyncPool->increaseSize($newSize);
	}

	/**
	 * @param Task $task
	 * @param int  $delay
	 *
	 * @return null|TaskHandler
	 */
	public function scheduleDelayedTask(Task $task, $delay){
		return $this->addTask($task, (int) $delay, -1);
	}

	/**
	 * @param Task $task
	 * @param int  $period
	 *
	 * @return null|TaskHandler
	 */
	public function scheduleRepeatingTask(Task $task, $period){
		return $this->addTask($task, -1, (int) $period);
	}

	/**
	 * @param Task $task
	 * @param int  $delay
	 * @param int  $period
	 *
	 * @return null|TaskHandler
	 */
	public function scheduleDelayedRepeatingTask(Task $task, $delay, $period){
		return $this->addTask($task, (int) $delay, (int) $period);
	}

	/**
	 * @param int $taskId
	 */
	public function cancelTask($taskId){
		if($taskId !== null and isset($this->tasks[$taskId])){
			$this->tasks[$taskId]->cancel();
			unset($this->tasks[$taskId]);
		}
	}

	/**
	 * @param Plugin $plugin
	 */
	public function cancelTasks(Plugin $plugin){
		foreach($this->tasks as $taskId => $task){
			$ptask = $task->getTask();
			if($ptask instanceof PluginTask and $ptask->getOwner() === $plugin){
				$task->cancel();
				unset($this->tasks[$taskId]);
			}
		}
	}

	public function cancelAllTasks(){
		foreach($this->tasks as $task){
			$task->cancel();
		}
		$this->tasks = [];
		$this->asyncPool->removeTasks();
		while(!$this->queue->isEmpty()){
			$this->queue->extract();
		}
		$this->ids = 1;
	}

	/**
	 * @param int $taskId
	 *
	 * @return bool
	 */
	public function isQueued($taskId){
		return isset($this->tasks[$taskId]);
	}

	/**
	 * @param Task $task
	 * @param      $delay
	 * @param      $period
	 *
	 * @return null|TaskHandler
	 *
	 * @throws PluginException
	 */
	private function addTask(Task $task, $delay, $period){
		if($task instanceof PluginTask and !$task->getOwner()->isEnabled()){
            throw new PluginException("Plugin '" . $task->getOwner()->getName() . "' tried to register a task while disabled");
        }

		if($delay <= 0){
			$delay = -1;
		}

		if($period <= -1){
			$period = -1;
		}elseif($period < 1){
			$period = 1;
		}

		return $this->handle(new TaskHandler(get_class($task), $task, $this->nextId(), $delay, $period));
	}

	private function handle(TaskHandler $handler) : TaskHandler{
		if($handler->isDelayed()){
			$nextRun = $this->currentTick + $handler->getDelay();
		}else{
			$nextRun = $this->currentTick;
		}

		$handler->setNextRun($nextRun);
		$this->tasks[$handler->getTaskId()] = $handler;
		$this->queue->insert($handler, $nextRun);

		return $handler;
	}

	public function downUnusedWorkers(){
		return $this->asyncPool->shutdownUnusedWorkers();
	}

	public function shutdown() : void{
		$this->cancelAllTasks();
		$this->asyncPool->shutdown();
	}

	/**
	 * @param int $currentTick
	 */
	public function mainThreadHeartbeat($currentTick){
		$this->currentTick = $currentTick;
		while($this->isReady($this->currentTick)){
			/** @var TaskHandler $task */
			$task = $this->queue->extract();
			if($task->isCancelled()){
				unset($this->tasks[$task->getTaskId()]);
				continue;
			}else{
				$task->timings->startTiming();
				try{
					$task->run($this->currentTick);
				}catch(\Throwable $e){
					Server::getInstance()->getLogger()->critical("Failed to complete task " . $task->getTaskName() . ": " . $e->getMessage());
					Server::getInstance()->getLogger()->logException($e);
				}
				$task->timings->stopTiming();
			}
			if($task->isRepeating()){
				$task->setNextRun($this->currentTick + $task->getPeriod());
				$this->queue->insert($task, $this->currentTick + $task->getPeriod());
			}else{
				$task->remove();
				unset($this->tasks[$task->getTaskId()]);
			}
		}

		$this->asyncPool->collectTasks();
	}

	private function isReady($currentTicks){
		return !$this->queue->isEmpty() and $this->queue->current()->getNextRun() <= $currentTicks;
	}

	/**
	 * @return int
	 */
	private function nextId(){
		return $this->ids++;
	}

}
