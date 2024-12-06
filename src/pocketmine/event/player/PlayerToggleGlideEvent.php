<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerToggleGlideEvent extends PlayerEvent implements Cancellable {

	public static $handlerList = null;
	/** @var bool */
	protected $isGliding;

	/**
	 * PlayerToggleGlideEvent constructor.
	 *
	 * @param Player $player
	 * @param        $isGliding
	 */
	public function __construct(Player $player, $isGliding){
		$this->player = $player;
		$this->isGliding = (bool) $isGliding;
	}

	/**
	 * @return bool
	 */
	public function isGliding(){
		return $this->isGliding;
	}

	/**
	 * @return EventName|string
	 */
	public function getName(){
		return "PlayerToggleGlideEvent";
	}

}
