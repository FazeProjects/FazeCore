<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleSprintEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var bool */
	protected $isSprinting;

	/**
	 * PlayerToggleSprintEvent constructor.
	 *
	 * @param Player $player
	 * @param        $isSprinting
	 */
	public function __construct(Player $player, $isSprinting){
		$this->player = $player;
		$this->isSprinting = (bool) $isSprinting;
	}

	/**
	 * @return bool
	 */
	public function isSprinting(){
		return $this->isSprinting;
	}

}