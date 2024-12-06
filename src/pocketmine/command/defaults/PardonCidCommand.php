<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;


class PardonCidCommand extends VanillaCommand {

	/**
	 * PardonCidCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.unban.cid.description",
			"/pardoncid <client-id>"
		);
		$this->setPermission("pocketmine.command.pardoncid");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $currentAlias
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) !== 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return false;
		}

		$sender->getServer()->getCIDBans()->remove($args[0]);

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.unbancid.success", [$args[0]]));

		return true;
	}
}
