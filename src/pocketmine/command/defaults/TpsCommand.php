<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

class TpsCommand extends VanillaCommand {

	/**
	 * TpsCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.tps.description",
			"%pocketmine.command.tps.usage"
		);
		$this->setPermission("pocketmine.command.tps");
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

		$rUsage = Utils::getRealMemoryUsage();
		$mUsage = Utils::getMemoryUsage(true);

		$server = $sender->getServer();
		$sender->sendMessage(TextFormat::AQUA . "---- " . TextFormat::WHITE . "Server TPS" . TextFormat::AQUA. " ----");

		$sender->sendMessage("§fCurrent TPS: §b{$server->getTicksPerSecond()} ({$server->getTickUsage()}%)");
		$sender->sendMessage("§fAverage TPS: §b{$server->getTicksPerSecondAverage()} ({$server->getTickUsageAverage()}%)");
	}
}
