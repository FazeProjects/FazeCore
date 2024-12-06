<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

class StatusCommand extends VanillaCommand {

	/**
	 * StatusCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.status.description",
			"%pocketmine.command.status.usage"
		);
		$this->setPermission("pocketmine.command.status");
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
		$sender->sendMessage(TextFormat::AQUA . "§f---- " . TextFormat::WHITE . "§6Server status" . TextFormat::AQUA . " §f----");

		$time = (int) (microtime(true) - \pocketmine\START_TIME);

		$seconds = $time % 60;
		$minutes = null;
		$hours = null;
		$days = null;

		if($time >= 60){
			$minutes = floor(($time % 3600) / 60);
			if($time >= 3600){
				$hours = floor(($time % (3600 * 24)) / 3600);
				if($time >= 3600 * 24){
					$days = floor($time / (3600 * 24));
				}
			}
		}

		$uptime = ($minutes !== null ?
				($hours !== null ?
					($days !== null ?
						"$days days "
					: "") . "$hours hours "
					: "") . "$minutes minutes "
			: "") . "$seconds seconds";

		$sender->sendMessage("§r§6Uptime: §f". $uptime);
		
		$sender->sendMessage("§6Current TPS: §f{$server->getTicksPerSecond()} ({$server->getTickUsage()}%)");
		$sender->sendMessage("§6Average TPS: §f{$server->getTicksPerSecondAverage()} ({$server->getTickUsageAverage()}%)");
 
		$onlineCount = 0;
		foreach($sender->getServer()->getOnlinePlayers() as $player){
			if($player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player))){
				++$onlineCount;
			}
		}
		
		$processorInfo = ""; 
        exec("cat /proc/cpuinfo | grep \"cpu MHz\" | awk '{print $4}'", $output); 
        if(!empty($output)){ 
            $processorFrequency = (float)$output[0]; 
            $processorInfo = "{$processorFrequency} MHz"; 
        }else{ 
            $processorInfo = "(nothing)"; 
        }
        
        $sender->sendMessage("§6Plugins count: §f". count($server->getPluginManager()->getPlugins()));

		$sender->sendMessage("§6Players Count: §f". $onlineCount . "/" . $sender->getServer()->getMaxPlayers());
		
		$sender->sendMessage("§6Network Upload: §f". \round($server->getNetwork()->getUpload() / 1024, 2) . " kB/s");
		
		$sender->sendMessage("§6Network Download: §f". \round($server->getNetwork()->getDownload() / 1024, 2) . " kB/s");
		
		$sender->sendMessage("§6CPU frequency: §f{$processorInfo}");
		
		$sender->sendMessage("§6Core count: §f". Utils::getCoreCount(true));
		
		$sender->sendMessage("§6Thread count: §f". Utils::getThreadCount());
		
		$sender->sendMessage("§6Main thread memory: §f". number_format(round(($mUsage[0] / 1024) / 1024, 2), 2) . " MB.");
		
		$sender->sendMessage("§6Total memory: §f". number_format(round(($mUsage[1] / 1024) / 1024, 2), 2) . " MB.");
		
		$sender->sendMessage("§6Total virtual memory: §f". number_format(round(($mUsage[2] / 1024) / 1024, 2), 2) . " MB.");
		
		$sender->sendMessage("§6Heap memory: §f". number_format(round(($rUsage[0] / 1024) / 1024, 2), 2) . " MB.");
		
		if($server->getProperty("memory.global-limit") > 0){
			$sender->sendMessage(TextFormat::GOLD . "%pocketmine.command.status.Maxmemorymanager " . TextFormat::RED . number_format(round($server->getProperty("memory.global-limit"), 2), 2) . " MB.");
		}
		
		foreach($server->getLevels() as $level){
			$levelName = $level->getFolderName() !== $level->getName() ? " (" . $level->getName() . ")" : "";
			$timeColor = $level->getTickRateTime() > 40 ? TextFormat::RED : TextFormat::YELLOW;
			$sender->sendMessage("§6". "World \"{$level->getFolderName()}\"$levelName: " .
			"§r§f" . number_format(count($level->getChunks())) . " §fchunks, §r§f". number_format(count($level->getEntities())) . " §fentities, §r§f". number_format(count($level->getTiles())) . " §ftiles, §r§fTime {$timeColor}". round($level->getTickRateTime(), 2) . "ms"
			);
		}

		return true;
	}
}
