<?php



namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SayCommand extends VanillaCommand {

	/**
	 * SayCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.say.description",
			"/say <message...>",
			["broadcast", "announce"]
		);
		$this->setPermission("pocketmine.command.say");
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

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$sender->getServer()->broadcastMessage(new TranslationContainer(TextFormat::GRAY . "%chat.type.announcement", [$sender instanceof Player ? $sender->getDisplayName() : ($sender instanceof ConsoleCommandSender ? "Server" : $sender->getName()), TextFormat::YELLOW . implode(" ", $args)]));
		return true;
	}
}
