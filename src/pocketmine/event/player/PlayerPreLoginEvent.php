<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player logs in, before everything has been set up.
 */
class PlayerPreLoginEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $kickMessage;

	/**
	 * PlayerPreLoginEvent constructor.
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