<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\Player;

/**
 * Called when a player runs a command or chats at the very beginning of a process
 *
 * You don't want to use this except in a few cases, like logging commands,
 * blocking commands in certain places, or applying modifiers.
 *
 * Message contains a leading slash
 */
class PlayerCommandPreprocessEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $message;


	/**
	 * @param Player $player
	 * @param string $message
	 */
	public function __construct(Player $player, $message){
		$this->player = $player;
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getMessage(){
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message){
		$this->message = $message;
	}

	/**
	 * @param Player $player
	 */
	public function setPlayer(Player $player){
		$this->player = $player;
	}

}