<?php



namespace pocketmine\command\defaults;

use pocketmine\command\{CommandSender, Command};
use pocketmine\inventory\{BaseInventory, PlayerInventory};
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class IdCommand extends VanillaCommand{
    public function __construct($name){
        parent::__construct(
			$name,
			"Find out the ID of the item in your hand",
			"/id"
		);
		$this->setPermission("pocketmine.command.id");
    }

    public function getId(Player $sender){
        $id = $sender->getInventory()->getItemInHand()->getId();
        return "{$id}";
    }

    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
			return true;
		}
		if(!($sender instanceof Player)){
			$sender->sendMessage("§cThe command is only available in the game!");
			return true;
		}else{
		    $id = $this->getId($sender);
		    $sender->sendMessage("§fID of item in hand: {$id}");
        }
    }
}