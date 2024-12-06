<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerUseFishingRodEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	const ACTION_START_FISHING = 0;
	const ACTION_STOP_FISHING = 1;

	private $action;

	/**
	 * PlayerUseFishingRodEvent constructor.
	 *
	 * @param Player $player
	 * @param int    $action
	 */
	public function __construct(Player $player, int $action = PlayerUseFishingRodEvent::ACTION_START_FISHING){
		$this->player = $player;
		$this->action = $action;
	}

	/**
	 * @return int
	 */
	public function getAction() : int{
		return $this->action;
	}
}