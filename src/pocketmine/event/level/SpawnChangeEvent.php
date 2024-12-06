<?php



namespace pocketmine\event\level;

use pocketmine\level\Level;
use pocketmine\level\Position;

/**
 * Event called when level spawn changes.
 * Previous spawn enabled
 */
class SpawnChangeEvent extends LevelEvent {
	public static $handlerList = null;

	/** @var Position */
	private $previousSpawn;

	/**
	 * @param Level    $level
	 * @param Position $previousSpawn
	 */
	public function __construct(Level $level, Position $previousSpawn){
		parent::__construct($level);
		$this->previousSpawn = $previousSpawn;
	}

	/**
	 * @return Position
	 */
	public function getPreviousSpawn(){
		return $this->previousSpawn;
	}
}