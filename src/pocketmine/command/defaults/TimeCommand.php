<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TimeCommand extends VanillaCommand {

	/**
	 * TimeCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.time.description",
			"/time <set|add> <value> ИЛИ /time <start|stop|query>"
		);
		$this->setPermission("pocketmine.command.time.add;pocketmine.command.time.set;pocketmine.command.time.start;pocketmine.command.time.stop");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $currentAlias
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(count($args) < 1){
			$sender->sendMessage($sender->getServer()->getLanguage()->translateString("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($args[0] === "start"){
			if(!$sender->hasPermission("pocketmine.command.time.start")){
				$sender->sendMessage($sender->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->startTime();
			}
			Command::broadcastCommandMessage($sender, "Restarted the time");
			return true;
		}elseif($args[0] === "stop"){
			if(!$sender->hasPermission("pocketmine.command.time.stop")){
				$sender->sendMessage($sender->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->stopTime();
			}
			Command::broadcastCommandMessage($sender, "Stopped the time");
			return true;
		}elseif($args[0] === "query"){
			if(!$sender->hasPermission("pocketmine.command.time.query")){
				$sender->sendMessage($sender->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			if($sender instanceof Player){
				$level = $sender->getLevel();
			}else{
				$level = $sender->getServer()->getDefaultLevel();
			}
			$sender->sendMessage($sender->getServer()->getLanguage()->translateString("commands.time.query", [$level->getTime()]));
			return true;
		}


		if(count($args) < 2){
			$sender->sendMessage($sender->getServer()->getLanguage()->translateString("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($args[0] === "set"){
			if(!$sender->hasPermission("pocketmine.command.time.set")){
				$sender->sendMessage($sender->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			switch($args[1]){
				case "day":
					$value = Level::TIME_DAY;
					break;
				case "noon":
					$value = Level::TIME_NOON;
					break;
				case "sunset":
					$value = Level::TIME_SUNSET;
					break;
				case "night":
					$value = Level::TIME_NIGHT;
					break;
				case "midnight":
					$value = Level::TIME_MIDNIGHT;
					break;
				case "sunrise":
					$value = Level::TIME_SUNRISE;
					break;
				default:
					$value = $this->getInteger($sender, $args[1], 0);
					break;
			}

			foreach($sender->getServer()->getLevels() as $level){
				$level->setTime($value);
			}
			Command::broadcastCommandMessage($sender, $sender->getServer()->getLanguage()->translateString("commands.time.set", [$value]));
		}elseif($args[0] === "add"){
			if(!$sender->hasPermission("pocketmine.command.time.add")){
				$sender->sendMessage($sender->getServer()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			$value = $this->getInteger($sender, $args[1], 0);
			foreach($sender->getServer()->getLevels() as $level){
				$level->setTime($level->getTime() + $value);
			}
			Command::broadcastCommandMessage($sender, $sender->getServer()->getLanguage()->translateString("commands.time.added", [$value]));
		}else{
			$sender->sendMessage($sender->getServer()->getLanguage()->translateString("commands.generic.usage", [$this->usageMessage]));
		}

		return true;
	}
}