<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player does an animation
 */
class PlayerAnimationEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	const ARM_SWING = 1;
	const WAKE_UP = 3;

	/** @var int */
	private $animationType;

	/**
	 * @param Player $player
	 * @param int    $animation
	 */
	public function __construct(Player $player, int $animation){
		$this->player = $player;
		$this->animationType = $animation;
	}

	/**
	 * @return int
	 */
	public function getAnimationType() : int{
		return $this->animationType;
	}

}
