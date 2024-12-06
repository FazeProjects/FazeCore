<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;


class SaveOnCommand extends VanillaCommand {

	/**
	 * SaveOnCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.saveon.description",
			"%pocketmine.command.saveon.usage"
		);
		$this->setPermission("pocketmine.command.save.enable");
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

		$sender->getServer()->setAutoSave(true);

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.enabled"));

		return true;
	}
}