<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PingCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Find out your ping",
			"/ping"
		);
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!($sender instanceof Player)){
			$sender->sendMessage(TextFormat::RED . "For players only!");
			return true;
		}
		
		$sender->sendPing();
		return true;
	}
}