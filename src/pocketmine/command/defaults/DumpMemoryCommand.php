<?php



namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;


class DumpMemoryCommand extends VanillaCommand {

	/**
	 * DumpMemoryCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"Dumps the memory",
			"/$name [path]"
		);
		$this->setPermission("pocketmine.command.dumpmemory");
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

		Command::broadcastCommandMessage($sender, "Dumping server memory");

		$sender->getServer()->getMemoryManager()->dumpServerMemory($args[0] ?? $sender->getServer()->getDataPath() . "/memory_dumps/memoryDump_" . date("D_M_j-H.i.s-T_Y", time()), 48, 80);
		return true;
	}

}
