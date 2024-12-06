<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player joins, after everything has been set up correctly (you can now change things)
 */
class PlayerLoginEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $kickMessage;

	/**
	 * PlayerLoginEvent constructor.
	 *
	 * @param Player $player
	 * @param        $kickMessage
	 */
	public function __construct(Player $player, $kickMessage){
		$this->player = $player;
		$this->kickMessage = $kickMessage;
	}

	/**
	 * @param $kickMessage
	 */
	public function setKickMessage($kickMessage){
		$this->kickMessage = $kickMessage;
	}

	/**
	 * @return string
	 */
	public function getKickMessage(){
		return $this->kickMessage;
	}

}