<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleFlightEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var bool */
	protected $isFlying;

	/**
	 * PlayerToggleFlightEvent constructor.
	 *
	 * @param Player $player
	 * @param        $isFlying
	 */
	public function __construct(Player $player, $isFlying){
		$this->player = $player;
		$this->isFlying = (bool) $isFlying;
	}

	/**
	 * @return bool
	 */
	public function isFlying(){
		return $this->isFlying;
	}

}