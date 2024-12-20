<?php



namespace pocketmine\scheduler;

use pocketmine\timings\Timings;
use pocketmine\timings\TimingsHandler;
use pocketmine\utils\MainLogger;

class TaskHandler{

	/** @var Task */
	protected $task;

	/** @var int */
	protected $taskId;

	/** @var int */
	protected $delay;

	/** @var int */
	protected $period;

	/** @var int */
	protected $nextRun;

	/** @var bool */
	protected $cancelled = false;

	/** @var TimingsHandler */
	public $timings;

	public $timingName = null;

	/**
	 * @param string $timingName
	 * @param Task   $task
	 * @param int    $taskId
	 * @param int    $delay
	 * @param int    $period
	 */
	public function __construct($timingName, Task $task, $taskId, $delay = -1, $period = -1){
		if($task->getHandler() !== null){
			throw new \InvalidArgumentException("It is not possible to assign multiple handlers to the same task.");
		}
		$this->task = $task;
		$this->taskId = $taskId;
		$this->delay = $delay;
		$this->period = $period;
		$this->timingName = $timingName ?? "Unknown";
		$this->timings = Timings::getPluginTaskTimings($this, $period);
		$this->task->setHandler($this);
	}

	public function isCancelled(){
		return $this->cancelled;
	}

	public function getNextRun(){
		return $this->nextRun;
	}

	/**
	 * @return void
	 */
	public function setNextRun($ticks){
		$this->nextRun = $ticks;
	}

	public function getTaskId() : int{
		return $this->taskId;
	}

	public function getTask(){
		return $this->task;
	}

	public function getDelay(){
		return $this->delay;
	}

	public function isDelayed(){
		return $this->delay > 0;
	}

	public function isRepeating(){
		return $this->period > 0;
	}

	public function getPeriod(){
		return $this->period;
	}

	/**
	 * @return void
	 */
	public function cancel(){
		try{
			if(!$this->isCancelled()){
				$this->task->onCancel();
			}
		}catch(\Throwable $e){
			MainLogger::getLogger()->logException($e);
		}finally{
			$this->remove();
		}
	}

	/**
	 * @return void
	 */
	public function remove(){
		$this->cancelled = true;
		$this->task->setHandler(null);
	}

	/**
	 * @return void
	 */
	public function run(int $currentTick){
		$this->task->onRun($currentTick);
	}

	public function getTaskName(){
		if($this->timingName !== null){
			return $this->timingName;
		}

		return $this->task->getName();
	}
}