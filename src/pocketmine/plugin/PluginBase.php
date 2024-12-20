<?php



namespace pocketmine\plugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Server;
use pocketmine\utils\Config;

abstract class PluginBase implements Plugin {

	/** @var PluginLoader */
	private $loader;

	/** @var \pocketmine\Server */
	private $server;

	/** @var bool */
	private $isEnabled = false;

	/** @var bool */
	private $initialized = false;

	/** @var PluginDescription */
	private $description;

	/** @var string */
	private $dataFolder;
	private $config;
	/** @var string */
	private $configFile;
	private $file;

	/** @var PluginLogger */
	private $logger;

    /**
     * Called when the plugin is loaded, before onEnable() is called
     */
	public function onLoad(){

	}

	public function onEnable(){

	}

	public function onDisable(){

	}

	/**
	 * @return bool
	 */
	public final function isEnabled(){
		return $this->isEnabled === true;
	}

	/**
	 * @param bool $boolean
	 */
	public final function setEnabled($boolean = true){
		if($this->isEnabled !== $boolean){
			$this->isEnabled = $boolean;
			if($this->isEnabled === true){
				$this->onEnable();
			}else{
				$this->onDisable();
			}
		}
	}

	/**
	 * @return bool
	 */
	public final function isDisabled(){
		return $this->isEnabled === false;
	}

	/**
	 * @return string
	 */
	public final function getDataFolder(){
		return $this->dataFolder;
	}

	/**
	 * @return PluginDescription
	 */
	public final function getDescription(){
		return $this->description;
	}

	/**
	 * @param PluginLoader      $loader
	 * @param Server            $server
	 * @param PluginDescription $description
	 * @param                   $dataFolder
	 * @param                   $file
	 */
	public final function init(PluginLoader $loader, Server $server, PluginDescription $description, $dataFolder, $file){
		if($this->initialized === false){
			$this->initialized = true;
			$this->loader = $loader;
			$this->server = $server;
			$this->description = $description;
		    $this->dataFolder = rtrim($dataFolder, "/" . DIRECTORY_SEPARATOR) . "/";
		    $this->file = rtrim($file, "/" . DIRECTORY_SEPARATOR) . "/";
			$this->configFile = $this->dataFolder . "config.yml";
			$this->logger = new PluginLogger($this);
		}
	}

	/**
	 * @return PluginLogger
	 */
	public function getLogger(){
		return $this->logger;
	}

	/**
	 * @return bool
	 */
	public final function isInitialized(){
		return $this->initialized;
	}

	/**
	 * @param string $name
	 *
	 * @return Command|PluginIdentifiableCommand
	 */
	public function getCommand($name){
		$command = $this->getServer()->getPluginCommand($name);
		if($command === null or $command->getPlugin() !== $this){
			$command = $this->getServer()->getPluginCommand(strtolower($this->description->getName()) . ":" . $name);
		}

		if($command instanceof PluginIdentifiableCommand and $command->getPlugin() === $this){
			return $command;
		}else{
			return null;
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param Command       $command
	 * @param string        $label
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		return false;
	}

	/**
	 * @return bool
	 */
	protected function isPhar(){
		return substr($this->file, 0, 7) === "phar://";
	}

    /**
     * Gets the embedded resource in the plugin file.
     * WARNING: You must close the resource specified with fclose()
     *
     * @param string $filename
     *
     * @return resource Resource data or null
     */
	public function getResource($filename){
		$filename = rtrim(str_replace(DIRECTORY_SEPARATOR, "/", $filename), "/");
		if(file_exists($this->file . "resources/" . $filename)){
			return fopen($this->file . "resources/" . $filename, "rb");
		}

		return null;
	}

	/**
	 * @param string $filename
	 * @param bool   $replace
	 *
	 * @return bool
	 */
	public function saveResource($filename, $replace = false){
		if(trim($filename) === ""){
			return false;
		}

		if(($resource = $this->getResource($filename)) === null){
			return false;
		}

		$out = $this->dataFolder . $filename;
		if(!file_exists(dirname($out))){
			mkdir(dirname($out), 0755, true);
		}

		if(file_exists($out) and $replace !== true){
			return false;
		}

		$ret = stream_copy_to_stream($resource, $fp = fopen($out, "wb")) > 0;
		fclose($fp);
		fclose($resource);
		return $ret;
	}

    /**
     * Returns all resources packaged with the plugin
     *
     * @return string[]
     */
	public function getResources(){
		$resources = [];
		if(is_dir($this->file . "resources/")){
			foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->file . "resources/")) as $resource){
				if($resource->isFile()){
					$resources[] = $resource;
				}
			}
		}

		return $resources;
	}

	/**
	 * @return Config
	 */
	public function getConfig(){
		if(!isset($this->config)){
			$this->reloadConfig();
		}

		return $this->config;
	}

	/**
	 *
	 */
	public function saveConfig(){
		if($this->getConfig()->save() === false){
			$this->getLogger()->critical("Failed to save configuration to " . $this->configFile);
		}
	}

	/**
	 *
	 */
	public function saveDefaultConfig(){
		if(!file_exists($this->configFile)){
			$this->saveResource("config.yml");
		}
	}

	/**
	 *
	 */
	public function reloadConfig(){
		if(!$this->saveDefaultConfig()){
			@mkdir($this->dataFolder);
		}
		$this->config = new Config($this->configFile);
	}

	/**
	 * @return Server
	 */
	public final function getServer(){
		return $this->server;
	}

	/**
	 * @return string
	 */
	public final function getName(){
		return $this->description->getName();
	}

	/**
	 * @return string
	 */
	public final function getFullName(){
		return $this->description->getFullName();
	}

	/**
	 * @return mixed
	 */
	public function getFile(){
		return $this->file;
	}

	/**
	 * @return PluginLoader
	 */
	public function getPluginLoader(){
		return $this->loader;
	}

}
