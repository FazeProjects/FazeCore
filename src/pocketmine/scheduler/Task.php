<?php



namespace pocketmine\scheduler;

use pocketmine\utils\Utils;

/**
 * WARNING: Tasks created by plugins MUST extend PluginTask.
 */
abstract class Task{

	/** @var TaskHandler */
	private $taskHandler = null;

	/**
	 * @return TaskHandler|null
	 */
	public final function getHandler(){
		return $this->taskHandler;
	}

	public final function getTaskId() : int{
		if($this->taskHandler !== null){
			return $this->taskHandler->getTaskId();
		}

		return -1;
	}

	public function getName() : string{
        try {
            return Utils::getNiceClassName($this);
        } catch (\ReflectionException $e) {
        }
        return false;
    }

	/**
	 * @return void
	 */
	public final function setHandler($taskHandler){
		if($this->taskHandler === null or $taskHandler === null){
			$this->taskHandler = $taskHandler;
		}
	}

	/**
	 * Actions performed at startup
	 *
	 * @return void
	 */
	public abstract function onRun($currentTick);

	/**
	 * Actions to take if a task is cancelled
	 *
	 * @return void
	 */
	public function onCancel(){

	}
}