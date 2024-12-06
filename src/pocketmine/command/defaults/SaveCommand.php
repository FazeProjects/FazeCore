<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use function microtime;
use function round;

class SaveCommand extends VanillaCommand{

	/**
	 * SaveCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.save.description",
			"%pocketmine.command.save.usage"
		);
		$this->setPermission("pocketmine.command.save.perform");
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

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.start"));
		$start = microtime(true);

		foreach($sender->getServer()->getOnlinePlayers() as $player){
			$player->save();
		}

		foreach($sender->getServer()->getLevels() as $level){
			$level->save(true);
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.success", [round(microtime(true) - $start, 3)]));

		return true;
	}
}