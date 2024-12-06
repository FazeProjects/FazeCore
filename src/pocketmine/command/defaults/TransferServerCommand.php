<?php



namespace pocketmine\command\defaults;

use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class TransferServerCommand extends VanillaCommand {

    /**
     * TransferServerCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "Teleport a player to another server",
            "/transferserver <player> <address> [port]",
            ["transferserver"]
        );
        $this->setPermission("pocketmine.command.transfer");
    }

    /**
     * @param CommandSender $sender
     * @param string        $currentAlias
     * @param array         $args
     *
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        $address = null;
        $port = null;
        $player = null;

        if($sender instanceof Player){
            if(!$this->testPermission($sender)){
                return true;
            }

            if(count($args) <= 0){
                $sender->sendMessage(TextFormat::RED . "Usage: /transferserver <address> [port]");
                return false;
            }

            $address = strtolower($args[0]);
            $port = (isset($args[1]) && is_numeric($args[1]) ? $args[1] : 19132);

            $pk = new TransferPacket();
            $pk->address = $address;
            $pk->port = $port;
            $sender->dataPacket($pk);

            return false;
        }

        if(count($args) <= 1){
            $sender->sendMessage(TextFormat::RED . "Usage: /transferserver <player> <address> [port]");
            return false;
        }

        if(!($player = Server::getInstance()->getPlayer($args[0])) instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Player specified not found!");
            return false;
        }

        $address = strtolower($args[1]);
        $port = (isset($args[2]) && is_numeric($args[2]) ? $args[2] : 19132);

        $sender->sendMessage(TextFormat::GREEN . "Sending " . $player->getName() . " to " . $address . ":" . $port);

        $pk = new TransferPacket();
        $pk->address = $address;
        $pk->port = $port;
        $player->dataPacket($pk);

        return true;
    }

}
