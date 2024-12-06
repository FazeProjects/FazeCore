<?php



namespace pocketmine\event\server;

use pocketmine\utils\Utils;


/**
 * Called when the server is in a low memory state, as defined in the properties
 * Plugins should free caches or other non-essential data.
 */
class LowMemoryEvent extends ServerEvent {
	public static $handlerList = null;

	private $memory;
	private $memoryLimit;
	private $triggerCount;
	private $global;

	/**
	 * LowMemoryEvent constructor.
	 *
	 * @param      $memory
	 * @param      $memoryLimit
	 * @param bool $isGlobal
	 * @param int  $triggerCount
	 */
	public function __construct($memory, $memoryLimit, $isGlobal = false, $triggerCount = 0){
		$this->memory = $memory;
		$this->memoryLimit = $memoryLimit;
		$this->global = (bool) $isGlobal;
		$this->triggerCount = (int) $triggerCount;
	}

	/**
     * Returns the memory usage at the time the event was called (in bytes)
     *
	 * @return int
	 */
	public function getMemory(){
		return $this->memory;
	}

	/**
     * Returns the specified memory limit (in bytes)
     *
	 * @return int
	 */
	public function getMemoryLimit(){
		return $this->memory;
	}

	/**
     * Returns the number of times this event has been called in the current low memory state.
     *
	 * @return int
	 */
	public function getTriggerCount(){
		return $this->triggerCount;
	}

	/**
	 * @return bool
	 */
	public function isGlobal(){
		return $this->global;
	}

	/**
     * The amount of memory already freed
     *
	 * @return int
	 */
	public function getMemoryFreed(){
		return $this->getMemory() - ($this->isGlobal() ? Utils::getMemoryUsage(true)[1] : Utils::getMemoryUsage(true)[0]);
	}

}