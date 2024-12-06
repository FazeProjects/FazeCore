<?php



namespace pocketmine\event\player;

use pocketmine\level\Position;
use pocketmine\Player;

/**
 * Called when the player respawns
 */
class PlayerRespawnEvent extends PlayerEvent {
	public static $handlerList = null;

	/** @var Position */
	protected $position;

	public function __construct(Player $player, Position $position){
		$this->player = $player;
		$this->position = $position;
	}

	public function getRespawnPosition() : Position{
		return $this->position;
	}

	public function setRespawnPosition(Position $position) : void{
		if(!$position->isValid()){
			throw new \InvalidArgumentException("The spawn position must reference a valid and loaded world.");
		}
		$this->position = $position;
	}
}