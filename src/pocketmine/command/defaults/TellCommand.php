<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TellCommand extends VanillaCommand {

    /**
     * TellCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "Send a private message to a player",
            "/m <player> <message>",
            ["w", "whisper", "msg", "m"]
        );
        $this->setPermission("pocketmine.command.tell");
    }

    /**
     * @param CommandSender $sender
     * @param string        $currentAlias
     * @param array         $args
     *
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if (!$this->testPermission($sender)) {
            return true;
        }

        if (count($args) < 2) {
            $sender->sendMessage(new TranslationContainer("Usage: " . TextFormat::YELLOW . $this->usageMessage));
            return false;
        }

        $playerName = array_shift($args);
        $player = $sender->getServer()->getPlayer($playerName);

        if ($player === $sender) {
            $sender->sendMessage(TextFormat::RED . "You cannot message yourself.");
            return true;
        }

        if ($player instanceof Player) {
            $message = implode(" ", $args);
            $sender->sendMessage(TextFormat::GRAY . "[" . TextFormat::GOLD . "You -> " . $player->getDisplayName() . TextFormat::GRAY . "] " . $message);
            $player->sendMessage(TextFormat::GRAY . "[" . TextFormat::GOLD . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> You" . TextFormat::GRAY . "] " . $message);
        } else {
            $sender->sendMessage(TextFormat::RED . "Player not found.");
        }

        return true;
    }
}