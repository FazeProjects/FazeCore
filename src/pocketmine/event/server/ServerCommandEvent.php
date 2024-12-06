<?php



namespace pocketmine\event\server;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;

/**
 * Called when the console runs a command at the start of a process
 *
 * You don't want to use this except in a few cases, such as logging commands,
 * blocking commands in certain places, or applying modifiers.
 *
 * The message contains a leading slash
 */
class ServerCommandEvent extends ServerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var string */
	protected $command;

	/** @var CommandSender */
	protected $sender;

	/**
	 * @param CommandSender $sender
	 * @param string        $command
	 */
	public function __construct(CommandSender $sender, $command){
		$this->sender = $sender;
		$this->command = $command;
	}

	/**
	 * @return CommandSender
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 * @return string
	 */
	public function getCommand(){
		return $this->command;
	}

	/**
	 * @param string $command
	 */
	public function setCommand($command){
		$this->command = $command;
	}

}