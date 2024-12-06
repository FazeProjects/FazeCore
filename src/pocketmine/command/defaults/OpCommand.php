<?php



namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class OpCommand extends VanillaCommand {

    /**
     * OpCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "%pocketmine.command.op.description",
            "/op <player>"
        );
        $this->setPermission("pocketmine.command.op.give");
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
            $sender->sendMessage(new TranslationContainer("/op <player>", [$this->usageMessage]));

            return false;
        }

        $name = array_shift($args);

        $player = $sender->getServer()->getOfflinePlayer($name);
        Command::broadcastCommandMessage($sender, new TranslationContainer("Player $name is now a server operator"));
        if($player instanceof Player){
            $player->sendMessage('You have been granted operator rights!');
        }
        $player->setOp(true);
        return true;
    }
}