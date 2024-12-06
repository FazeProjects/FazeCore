<?php



namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DeopCommand extends VanillaCommand {

    /**
     * DeopCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "%pocketmine.command.deop.description",
            "/deop <player>"
        );
        $this->setPermission("pocketmine.command.op.take");
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
            $sender->sendMessage(new TranslationContainer("/deop <player>", [$this->usageMessage]));

            return false;
        }

        $name = array_shift($args);

        $player = $sender->getServer()->getOfflinePlayer($name);
        $player->setOp(false);
        if($player instanceof Player){
            $player->sendMessage(TextFormat::GRAY . "Your operator rights have been revoked.");
        }
        Command::broadcastCommandMessage($sender, new TranslationContainer("§fThe operator rights have been revoked from this player", [$player->getName()]));

        return true;
    }
}
