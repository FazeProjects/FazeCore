<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Server;

class KillAllCommand extends VanillaCommand {

	/**
	 * KillAllCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.killall.description",
			"/killall"
		);
		$this->setPermission("pocketmine.command.killall.self;pocketmine.command.killall.other");
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
        $server = $sender->getServer();
        foreach($server->getLevels() as $level){
            foreach($level->getEntities() as $entity){
                if($entity instanceof \pocketmine\entity\Living){
                    $sender->sendMessage("All entities have been purified.");
                    $entity->kill();
                }
            }
        }
    }
}
