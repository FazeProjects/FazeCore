<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 *  _____            _               _____
 * / ____|          (_)             |  __ \
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/
 *                         __/ |
 *                        |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/

namespace pocketmine\resourcepacks;

use pocketmine\utils\Config;
use pocketmine\Server;
use function array_keys;
use function copy;
use function count;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function gettype;
use function is_array;
use function is_dir;
use function is_float;
use function is_int;
use function is_string;
use function mkdir;
use function strtolower;
use const DIRECTORY_SEPARATOR;

class ResourcePackManager{

	private $server;

	/** @var string */
	private $path;

	/** @var bool */
	private $serverForceResources = false;

	/** @var ResourcePack[] */
	private $resourcePacks = [];

	/** @var ResourcePack[] */
	private $uuidList = [];

	/**
	 * @param string  $path Path to resource-packs directory.
	 */
	public function __construct(Server $server, string $path, \Logger $logger){
		$this->path = $path;

		$this->server = $server;

		if(!file_exists($this->path)){
			$logger->debug($this->server->getLanguage()->translateString("pocketmine.resourcepacks.createFolder", [$path]));
			mkdir($this->path);
		}elseif(!is_dir($this->path)){
			throw new \InvalidArgumentException($this->server->getLanguage()->translateString("pocketmine.resourcepacks.notFolder", [$path]));
		}

		if(!file_exists($this->path . "resource_packs.yml")){
			$lang = $this->server->getProperty("settings.language");
			if(file_exists($this->server->getFilePath() . "src/pocketmine/resources/resource_packs_$lang.yml")){
				$content = file_get_contents($file = $this->server->getFilePath() . "src/pocketmine/resources/resource_packs_$lang.yml");
			}else{
				$content = file_get_contents($file = $this->server->getFilePath() . "src/pocketmine/resources/resource_packs_eng.yml");
			}
			file_put_contents($this->path . "resource_packs.yml", $content);
		}

		$resourcePacksConfig = new Config($this->path . "resource_packs.yml", Config::YAML, []);

		$this->serverForceResources = (bool) $resourcePacksConfig->get("force_resources", false);

		$logger->info($this->server->getLanguage()->translateString("pocketmine.resourcepacks.load"));

		$resourceStack = $resourcePacksConfig->get("resource_stack", []);
		if(!is_array($resourceStack)){
			throw new \InvalidArgumentException("\"resource_stack\" key should contain a list of pack names");
		}

		foreach($resourceStack as $pos => $pack){
			if(!is_string($pack) && !is_int($pack) && !is_float($pack)){
				$logger->critical("Found invalid entry in resource pack list at offset $pos of type " . gettype($pack));
				continue;
			}
			$pack = (string) $pack;
			try{
				$packPath = $this->path . DIRECTORY_SEPARATOR . $pack;
				if(!file_exists($packPath)){
					throw new ResourcePackException("File or directory not found");
				}
				if(is_dir($packPath)){
					throw new ResourcePackException("Directory resource packs are unsupported");
				}

				$newPack = null;
				//Detect the type of resource pack.
				$info = new \SplFileInfo($packPath);
				switch($info->getExtension()){
					case "zip":
					case "mcpack":
						$newPack = new ZippedResourcePack($packPath);
						break;
				}

				if($newPack instanceof ResourcePack){
					$this->resourcePacks[] = $newPack;
					$this->uuidList[strtolower($newPack->getPackId())] = $newPack;
				}else{
					throw new ResourcePackException("Format not recognized");
				}
			}catch(ResourcePackException $e){
				$logger->critical("Could not load resource pack \"$pack\": " . $e->getMessage());
			}
		}

		$logger->debug($this->server->getLanguage()->translateString("pocketmine.resourcepacks.loadFinished", [count($this->resourcePacks)]));
	}

	/**
	 * Returns the directory which resource packs are loaded from.
	 */
	public function getPath() : string{
		return $this->path;
	}

	/**
	 * Returns whether players must accept resource packs in order to join.
	 */
	public function resourcePacksRequired() : bool{
		return $this->serverForceResources;
	}

	/**
	 * Returns an array of resource packs in use, sorted in order of priority.
	 * @return ResourcePack[]
	 */
	public function getResourceStack() : array{
		return $this->resourcePacks;
	}

	/**
	 * Returns the resource pack matching the specified UUID string, or null if the ID was not recognized.
	 *
	 * @return ResourcePack|null
	 */
	public function getPackById(string $id){
		return $this->uuidList[strtolower($id)] ?? null;
	}

	/**
	 * Returns an array of pack IDs for packs currently in use.
	 * @return string[]
	 */
	public function getPackIdList() : array{
		return array_keys($this->uuidList);
	}
}