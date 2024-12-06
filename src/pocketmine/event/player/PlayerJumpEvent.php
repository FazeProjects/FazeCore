<?php



namespace pocketmine\event\player;

use pocketmine\Player;

/**
 * Called when the player jumps.
 */
class PlayerJumpEvent extends PlayerEvent{
	public static $handlerList = null;

	/**
	 * PlayerJumpEvent constructor.
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}
}