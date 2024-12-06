<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player leaves the server.
 */
class PlayerKickEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $quitMessage;

	/** @var string */
	protected $reason;

	/**
	 * PlayerKickEvent constructor.
	 *
	 * @param Player $player
	 * @param        $reason
	 * @param        $quitMessage
	 */
	public function __construct(Player $player, $reason, $quitMessage){
		$this->player = $player;
		$this->quitMessage = $quitMessage;
		$this->reason = $reason;
	}

	/**
	 * @return string
	 */
	public function getReason(){
		return $this->reason;
	}

	/**
	 * @param $quitMessage
	 */
	public function setQuitMessage($quitMessage){
		$this->quitMessage = $quitMessage;
	}

	/**
	 * @return string
	 */
	public function getQuitMessage(){
		return $this->quitMessage;
	}

}