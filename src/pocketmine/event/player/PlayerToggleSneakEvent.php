<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleSneakEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var bool */
	protected $isSneaking;

	/**
	 * PlayerToggleSneakEvent constructor.
	 *
	 * @param Player $player
	 * @param        $isSneaking
	 */
	public function __construct(Player $player, $isSneaking){
		$this->player = $player;
		$this->isSneaking = (bool) $isSneaking;
	}

	/**
	 * @return bool
	 */
	public function isSneaking(){
		return $this->isSneaking;
	}

}