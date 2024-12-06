<?php



namespace pocketmine\scheduler;

use pocketmine\Collectable;
use pocketmine\Server;
use pocketmine\utils\AssumptionFailedError;
use function is_scalar;
use function is_string;
use function serialize;
use function unserialize;

/**
 * Class used to run async tasks in other threads.
 *
 * An AsyncTask does not have its own thread. It is queued into an AsyncPool and executed if there is an async worker
 * with no AsyncTask running. Therefore, an AsyncTask SHOULD NOT execute for more than a few seconds. For tasks that
 * run for a long time or infinitely, start another {@link \pocketmine\Thread} instead.
 *
 * WARNING: Do not call PocketMine-MP API methods, or save objects (and arrays containing objects) from/on other Threads!!
 */
abstract class AsyncTask extends Collectable{
	/**
	 * @var \SplObjectStorage|null
	 * @phpstan-var \SplObjectStorage<AsyncTask, mixed>
	 */
	private static $threadLocalStorage;

	/** @var AsyncWorker $worker */
	public $worker = null;

	/** @var \Threaded */
	public $progressUpdates;

	/** @var scalar|null */
	private $result = null;
	/** @var bool */
	private $serialized = false;
	/** @var bool */
	private $cancelRun = false;
	/** @var int|null */
	private $taskId = null;

	/** @var bool */
	private $crashed = false;

	private $isGarbage = false;

	/**
	 * @return bool
	 */
	public function isGarbage() : bool{
		return $this->isGarbage;
	}

	public function setGarbage(){
		$this->isGarbage = true;
	}

	/**
	 * @return void
	 */
	public function run(){
		$this->result = null;
		$this->isGarbage = false;

		if(!$this->cancelRun){
			try{
				$this->onRun();
			}catch(\Throwable $e){
				$this->crashed = true;
				$this->worker->handleException($e);
			}
		}

		$this->setGarbage();
	}

	/**
	 * @return bool
	 */
	public function isCrashed(){
		return $this->crashed or $this->isTerminated();
	}

	/**
	 * @return mixed
	 */
	public function getResult(){
		if($this->serialized){
			if(!is_string($this->result)) throw new AssumptionFailedError("The result is expected to be a serialized string.");
			return unserialize($this->result);
		}
		return $this->result;
	}

	/**
	 * @return void
	 */
	public function cancelRun(){
		$this->cancelRun = true;
	}

	public function hasCancelledRun() : bool{
		return $this->cancelRun === true;
	}

	public function hasResult() : bool{
		return $this->result !== null;
	}

	/**
	 * @param mixed $result
	 *
	 * @return void
	 */
    public function setResult($result){
        $this->result = ($this->serialized = !is_scalar($result)) ? serialize($result) : $result;
	}

	/**
	 * @return void
	 */
	public function setTaskId(int $taskId){
		$this->taskId = $taskId;
	}

	/**
	 * @return int|null
	 */
	public function getTaskId(){
		return $this->taskId;
	}

	/**
	 * @deprecated
	 * @see AsyncWorker::getFromThreadStore()
	 *
	 * @return mixed
	 */
	public function getFromThreadStore(string $identifier){
		if($this->worker === null or $this->isGarbage()){
			throw new \BadMethodCallException("Objects stored in the AsyncWorker's thread-local storage can only be retrieved while the task is running.");
		}
		return $this->worker->getFromThreadStore($identifier);
	}

	/**
	 * @deprecated
	 * @see AsyncWorker::saveToThreadStore()
	 *
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function saveToThreadStore(string $identifier, $value){
		if($this->worker === null or $this->isGarbage()){
			throw new \BadMethodCallException("Objects can only be added to the AsyncWorker's thread-local storage while the task is running.");
		}
		$this->worker->saveToThreadStore($identifier, $value);
	}

	/**
	 * @deprecated
	 * @see AsyncWorker::removeFromThreadStore()
	 */
	public function removeFromThreadStore(string $identifier) : void{
		if($this->worker === null or $this->isGarbage()){
			throw new \BadMethodCallException("Objects can only be deleted from the AsyncWorker's thread-local storage while the task is running.");
		}
		$this->worker->removeFromThreadStore($identifier);
	}

    /**
     * Actions to perform on startup
     *
	 * @return void
	 */
	public abstract function onRun();

	/**
     * Actions to perform after completion (on the main thread)
     * Implement this if you want to process data in your AsyncTask after it has been processed.
     *
	 * @return void
	 */
	public function onCompletion(Server $server){

	}

    /**
     * Call this method from {@link AsyncTask::onRun} (the execution thread of AsyncTask) to schedule a call
     * to {@link AsyncTask::onProgressUpdate} from the main thread with the given progress parameter.
     *
     * @param mixed $progress A value that can be safely serialized().
     *
     * @return void
     */
    public function publishProgress($progress){
        $this->progressUpdates[] = serialize($progress);
    }

    /**
     * @internal Only called from AsyncPool.php in the main thread
     *
     * @param Server $server
     */
    public function checkProgressUpdates(Server $server){
        while($this->progressUpdates->count() !== 0){
            $progress = $this->progressUpdates->shift();
            $this->onProgressUpdate($server, unserialize($progress));
        }
    }

    /**
     * Called from the main thread after calling {@link AsyncTask#publishProgress}.
     * All calls to {@link AsyncTask#publishProgress} must result in calls to {@link AsyncTask#onProgressUpdate}
     * before {@link AsyncTask#onCompletion} is called.
     *
     * @param Server $server
     * @param \Threaded|mixed $progress The parameter passed to {@link AsyncTask#publishProgress}. If it is not
     * a threaded object, it will be serialized(), then deserialized() as if it were cloned.
     */
    public function onProgressUpdate(Server $server, $progress){

    }

    /**
     * Saves mixed data to the thread's local storage in the parent thread. You can use this to store references to objects
     * or arrays that you need to access in {@link AsyncTask#onCompletion}, which cannot be saved as properties
     * of your task (due to serialization).
     *
     * Scalar types can be stored directly in class properties instead of using this storage.
     *
     * WARNING: THIS METHOD MUST ONLY BE CALLED FROM THE MAIN THREAD!
     *
     * @param mixed $complexData Data to store
     *
     * @throws \BadMethodCallException if called from any thread other than the main thread
     */
    protected function storeLocal($complexData){
        if($this->worker !== null and $this->worker === \Thread::getCurrentThread()){
            throw new \BadMethodCallException("Objects can only be stored from the parent thread");
        }

        if(self::$threadLocalStorage === null){
            self::$threadLocalStorage = new \SplObjectStorage(); // lazy init
        }

        if(isset(self::$threadLocalStorage[$this])){
            throw new \InvalidStateException("Complex data is already stored for this asynchronous task");
        }
        self::$threadLocalStorage[$this] = $complexData;
    }

    /**
     * Returns data previously stored in the thread's local storage in the parent thread. Use this during progress updates or
     * task completion to retrieve data you stored using {@link AsyncTask::storeLocal}.
     *
     * WARNING: THIS METHOD MUST ONLY BE CALLED FROM THE MAIN THREAD!
     *
     * @return mixed
     *
     * @throws \RuntimeException if this AsyncTask instance has not stored any data.
     * @throws \BadMethodCallException if called from any thread other than the main thread
     */
    protected function fetchLocal(){
        if($this->worker !== null and $this->worker === \Thread::getCurrentThread()){
            throw new \BadMethodCallException("Objects can only be retrieved from the parent thread");
        }

        if(self::$threadLocalStorage === null or !isset(self::$threadLocalStorage[$this])){
            throw new \InvalidStateException("No complex data is stored for this asynchronous task.");
        }

        return self::$threadLocalStorage[$this];
    }

    /**
     * @deprecated
     * @see AsyncTask::fetchLocal()
     *
     * @return mixed
     *
     * @throws \RuntimeException if no data has been stored by this AsyncTask instance
     * @throws \BadMethodCallException if called from any thread other than the main thread
     */
    protected function peekLocal(){
        return $this->fetchLocal();
    }

    /**
     * @internal Called by AsyncPool to destroy any remaining stored objects that this task was unable to retrieve.
     */
    public function removeDanglingStoredObjects() : void{
        if(self::$threadLocalStorage !== null and isset(self::$threadLocalStorage[$this])){
            unset(self::$threadLocalStorage[$this]);
        }
    }
}