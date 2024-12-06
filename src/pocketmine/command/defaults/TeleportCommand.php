<?php

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TeleportCommand extends VanillaCommand {

    /**
     * TeleportCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "Teleport a player to another player or to specific coordinates.",
            "/tp [target player] <destination player> or /tp [target player] <x> <y> <z> [<y-rot> <x-rot>]"
        );
        $this->setPermission("pocketmine.command.teleport");
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

        if (count($args) < 1 || count($args) > 6) {
            $sender->sendMessage(TextFormat::YELLOW . "Usage: " . TextFormat::WHITE . $this->usageMessage);
            return true;
        }

        $target = null;
        $origin = $sender;

        if (count($args) === 1 || count($args) === 3 || count($args) === 5) {
            if ($sender instanceof Player) {
                $target = $sender;
            } else {
                $sender->sendMessage(TextFormat::RED . "Please specify a player!");
                return true;
            }

            if (count($args) === 1) {
                $target = $sender->getServer()->getPlayer($args[0]);
                if ($target === null) {
                    $sender->sendMessage(TextFormat::RED . "Player " . $args[0] . " not found!");
                    return true;
                }
            }
        } else {
            $target = $sender->getServer()->getPlayer($args[0]);
            if ($target === null) {
                $sender->sendMessage(TextFormat::RED . "Player " . $args[0] . " not found!");
                return true;
            }

            if (count($args) === 2) {
                $origin = $target;
                $target = $sender->getServer()->getPlayer($args[1]);
                if ($target === null) {
                    $sender->sendMessage(TextFormat::RED . "Player " . $args[1] . " not found!");
                    return true;
                }
            }
        }

        if (count($args) < 3) {
            $origin->teleport($target);
            Command::broadcastCommandMessage($sender, TextFormat::GREEN . $origin->getName() . " was teleported to " . $target->getName() . ".");
            return true;
        } elseif ($target->isValid()) {
            $pos = (count($args) === 4 || count($args) === 6) ? 1 : 0;

            $x = $this->getRelativeDouble($target->x, $sender, $args[$pos++]);
            $y = $this->getRelativeDouble($target->y, $sender, $args[$pos++], 0, 256);
            $z = $this->getRelativeDouble($target->z, $sender, $args[$pos++]);
            $yaw = $target->getYaw();
            $pitch = $target->getPitch();

            if (count($args) === 6 || (count($args) === 5 && $pos === 3)) {
                $yaw = (float)$args[$pos++];
                $pitch = (float)$args[$pos++];
            }

            $target->teleport(new Vector3($x, $y, $z), $yaw, $pitch);
            Command::broadcastCommandMessage($sender, TextFormat::GREEN . $target->getName() . " was teleported to coordinates (" . round($x, 2) . ", " . round($y, 2) . ", " . round($z, 2) . ").");
            return true;
        }

        $sender->sendMessage(TextFormat::YELLOW . "Usage: " . TextFormat::WHITE . $this->usageMessage);
        return true;
    }
}
