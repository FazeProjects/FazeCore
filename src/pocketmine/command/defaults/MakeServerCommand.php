<?php



namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\ProtocolInfo;

class MakeServerCommand extends VanillaCommand {

	/**
	 * MakeServerCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"Creates a PocketMine Phar",
			"/makeserver"
		);
		$this->setPermission("pocketmine.command.makeserver");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}

		$server = $sender->getServer();
		$pharPath = Server::getInstance()->getPluginPath() . DIRECTORY_SEPARATOR . "FazeCore" . DIRECTORY_SEPARATOR . $server->getName() . "_" . $server->getPocketMineVersion() . "_" . date("Y-m-d") . ".phar";
		if(file_exists($pharPath)){
			$sender->sendMessage("Phar file already exists, overwriting...");
			@unlink($pharPath);
		}
		$phar = new \Phar($pharPath);
		$phar->setMetadata([
			"name" => $server->getName(),
			"version" => $server->getPocketMineVersion(),
			"api" => $server->getApiVersion(),
			"minecraft" => $server->getVersion(),
			"protocol" => ProtocolInfo::CURRENT_PROTOCOL,
			"creationDate" => time()
		]);
		$phar->setStub('<?php define("pocketmine\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."/src/pocketmine/PocketMine.php");  __HALT_COMPILER();');
		$phar->setSignatureAlgorithm(\Phar::SHA1);
		$phar->startBuffering();

		$filePath = substr(\pocketmine\PATH, 0, 7) === "phar://" ? \pocketmine\PATH : realpath(\pocketmine\PATH) . "/";
		$filePath = rtrim(str_replace("\\", "/", $filePath), "/") . "/";
		if(is_dir($filePath . ".git")){
			// Add some Git files as they are required in getting GIT_COMMIT
			foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . ".git")) as $file){
				$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
				if((strpos($path, ".git/HEAD") === false and strpos($path, ".git/refs/heads") === false) or strpos($path, "/.") !== false){
					continue;
				}
				$phar->addFile($file, $path);
				$sender->sendMessage("[FazeCore] Adding $path");
			}
		}
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . "src")) as $file){
			$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
			if($path[0] === "." or strpos($path, "/.") !== false or substr($path, 0, 4) !== "src/"){
				continue;
			}
			$phar->addFile($file, $path);
			$sender->sendMessage("[FazeCore] Adding $path");
		}
		foreach($phar as $file => $finfo){
			/** @var \PharFileInfo $finfo */
			if($finfo->getSize() > (1024 * 512)){
				$finfo->compress(\Phar::GZ);
			}
		}
		$phar->stopBuffering();

		$license = "FazeCore";
		$sender->sendMessage($license);
		$sender->sendMessage($server->getName() . " " . $server->getPocketMineVersion() . " Phar file has been created on " . $pharPath);

		return true;
	}
}
