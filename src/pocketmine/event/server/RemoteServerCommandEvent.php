<?php



namespace pocketmine\event\server;

use pocketmine\command\CommandSender;

/**
 * This event is fired when a command is received via RCON.
 */
class RemoteServerCommandEvent extends ServerCommandEvent {
	public static $handlerList = null;

	/**
	 * @param CommandSender $sender
	 * @param string        $command
	 */
	public function __construct(CommandSender $sender, $command){
		parent::__construct($sender, $command);
	}

}