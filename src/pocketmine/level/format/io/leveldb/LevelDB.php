<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\level\format\io\leveldb;

use pocketmine\timings\LevelTimings;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\io\BaseLevelProvider;
use pocketmine\level\format\io\ChunkUtils;
use pocketmine\level\format\SubChunk;
use pocketmine\level\generator\Flat;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\level\LevelException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\LongTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\Binary;
use pocketmine\utils\MainLogger;

class LevelDB extends BaseLevelProvider {

	//According to Tomasso, these aren't supposed to be readable anymore. Thankfully he didn't change the readable ones...
	const TAG_DATA_2D = "\x2d";
	const TAG_DATA_2D_LEGACY = "\x2e";
	const TAG_SUBCHUNK_PREFIX = "\x2f";
	const TAG_LEGACY_TERRAIN = "0";
	const TAG_BLOCK_ENTITY = "1";
	const TAG_ENTITY = "2";
	const TAG_PENDING_TICK = "3";
	const TAG_BLOCK_EXTRA_DATA = "4";
	const TAG_BIOME_STATE = "5";

	const TAG_VERSION = "v";

	const ENTRY_FLAT_WORLD_LAYERS = "game_flatworldlayers";

	const GENERATOR_LIMITED = 0;
	const GENERATOR_INFINITE = 1;
	const GENERATOR_FLAT = 2;

	const CURRENT_STORAGE_VERSION = 5; //Current MCPE level format version

	/** @var \LevelDB */
	protected $db;

    /**
     * LevelDB constructor.
     *
     * @param string $path
     * @param LevelTimings|null $timings
     * @noinspection PhpMissingParentConstructorInspection
     */
	public function __construct(string $path, LevelTimings $timings = null){
	    $this->path = $path;
	    $this->timings = $timings;

		if(!file_exists($this->path)){
			mkdir($this->path, 0777, true);
		}
		$rawLevelData = file_get_contents($this->getPath() . "level.dat");
		if($rawLevelData === false or strlen($rawLevelData) <= 8){
			throw new LevelException("Truncated level.dat");
		}
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->read(substr($rawLevelData, 8));
		$levelData = $nbt->getData();
		if($levelData instanceof CompoundTag){
			$this->levelData = $levelData;
		}else{
			throw new LevelException("Invalid level.dat");
		}

		$this->db = new \LevelDB($this->path . "/db", [
			"compression" => LEVELDB_ZLIB_COMPRESSION
		]);

		if(isset($this->levelData->StorageVersion) and $this->levelData->StorageVersion->getValue() > self::CURRENT_STORAGE_VERSION){
			throw new LevelException("Specified LevelDB world format version is newer than the version supported by the server");
		}

		if(!isset($this->levelData->generatorName)){
			if(isset($this->levelData->Generator)){
				switch((int) $this->levelData->Generator->getValue()){ //Detect correct generator from MCPE data
					case self::GENERATOR_FLAT:
						$this->levelData->generatorName = new StringTag("generatorName", GeneratorManager::getGenerator("FLAT"));
						if(($layers = $this->db->get(self::ENTRY_FLAT_WORLD_LAYERS)) !== false){ //Detect existing custom flat layers
							$layers = trim($layers, "[]");
						}else{
							$layers = "7,3,3,2";
						}
						$this->levelData->generatorOptions = new StringTag("generatorOptions", "2;" . $layers . ";1");
						break;
					case self::GENERATOR_INFINITE:
						//TODO: add a null generator which does not generate missing chunks (to allow importing back to MCPE and generating more normal terrain without PocketMine messing things up)
						$this->levelData->generatorName = new StringTag("generatorName", GeneratorManager::getGenerator("DEFAULT"));
						$this->levelData->generatorOptions = new StringTag("generatorOptions", "");
						break;
					case self::GENERATOR_LIMITED:
						throw new LevelException("Limited worlds are not currently supported");
					default:
						throw new LevelException("Unknown LevelDB world format type, this level cannot be loaded");
				}
			}else{
				$this->levelData->generatorName = new StringTag("generatorName", GeneratorManager::getGenerator("DEFAULT"));
			}
		}

		if(!isset($this->levelData->generatorOptions)){
			$this->levelData->generatorOptions = new StringTag("generatorOptions", "");
		}
	}

	/**
	 * @return string
	 */
	public static function getProviderName() : string{
		return "leveldb";
	}

	/**
	 * @return int
	 */
	public function getWorldHeight() : int{
		return 256;
	}

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	public static function isValid(string $path) : bool{
		return file_exists($path . "/level.dat") and is_dir($path . "/db/");
	}

	/**
	 * @param string     $path
	 * @param string     $name
	 * @param int|string $seed
	 * @param string     $generator
	 * @param array      $options
	 */
	public static function generate(string $path, string $name, $seed, string $generator, array $options = []){
		if(!file_exists($path . "/db")){
			mkdir($path . "/db", 0777, true);
		}

		switch($generator){
			case Flat::class:
				$generatorType = self::GENERATOR_FLAT;
				break;
			default:
				$generatorType = self::GENERATOR_INFINITE;
			//TODO: add support for limited worlds
		}

		$levelData = new CompoundTag("", [
			//Vanilla fields
			"DayCycleStopTime" => new IntTag("DayCycleStopTime", -1),
			"Difficulty" => new IntTag("Difficulty", 2),
			"ForceGameType" => new ByteTag("ForceGameType", 0),
			"GameType" => new IntTag("GameType", 0),
			"Generator" => new IntTag("Generator", $generatorType),
			"LastPlayed" => new LongTag("LastPlayed", time()),
			"LevelName" => new StringTag("LevelName", $name),
			"NetworkVersion" => new IntTag("NetworkVersion", ProtocolInfo::CURRENT_PROTOCOL),
			//"Platform" => new IntTag("Platform", 2), //TODO: find out what the possible values are for
			"RandomSeed" => new LongTag("RandomSeed", $seed),
			"SpawnX" => new IntTag("SpawnX", 0),
			"SpawnY" => new IntTag("SpawnY", 32767),
			"SpawnZ" => new IntTag("SpawnZ", 0),
			"StorageVersion" => new IntTag("StorageVersion", self::CURRENT_STORAGE_VERSION),
			"Time" => new LongTag("Time", 0),
			"eduLevel" => new ByteTag("eduLevel", 0),
			"falldamage" => new ByteTag("falldamage", 1),
			"firedamage" => new ByteTag("firedamage", 1),
			"hasBeenLoadedInCreative" => new ByteTag("hasBeenLoadedInCreative", 1), //badly named, this actually determines whether achievements can be earned in this world...
			"immutableWorld" => new ByteTag("immutableWorld", 0),
			"lightningLevel" => new FloatTag("lightningLevel", 0.0),
			"lightningTime" => new IntTag("lightningTime", 0),
			"pvp" => new ByteTag("pvp", 1),
			"rainLevel" => new FloatTag("rainLevel", 0.0),
			"rainTime" => new IntTag("rainTime", 0),
			"spawnMobs" => new ByteTag("spawnMobs", 1),
			"texturePacksRequired" => new ByteTag("texturePacksRequired", 0), //TODO

			//Additional PocketMine-MP fields
			"GameRules" => new CompoundTag("GameRules", []),
			"hardcore" => new ByteTag("hardcore", 0),
			"generatorName" => new StringTag("generatorName", GeneratorManager::getGeneratorName($generator)),
			"generatorOptions" => new StringTag("generatorOptions", $options["preset"] ?? "")
		]);

		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($levelData);
		$buffer = $nbt->write();
		file_put_contents($path . "level.dat", Binary::writeLInt(self::CURRENT_STORAGE_VERSION) . Binary::writeLInt(strlen($buffer)) . $buffer);


		$db = new \LevelDB($path . "/db", [
			"compression" => LEVELDB_ZLIB_COMPRESSION
		]);

		if($generatorType === self::GENERATOR_FLAT and isset($options["preset"])){
			$layers = explode(";", $options["preset"])[1] ?? "";
			if($layers !== ""){
				$out = "[";
				foreach(Flat::parseLayers($layers) as $result){
					$out .= $result[0] . ","; //only id, meta will unfortunately not survive :(
				}
				$out = rtrim($out, ",") . "]"; //remove trailing comma
				$db->put(self::ENTRY_FLAT_WORLD_LAYERS, $out); //Add vanilla flatworld layers to allow terrain generation by MCPE to continue seamlessly
			}
		}
	}

	public function saveLevelData(){
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($this->levelData);
		$buffer = $nbt->write();
		file_put_contents($this->getPath() . "level.dat", Binary::writeLInt(self::CURRENT_STORAGE_VERSION) . Binary::writeLInt(strlen($buffer)) . $buffer);
	}

	/**
	 * @return string
	 */
	public function getGenerator() : string{
		return $this->levelData["generatorName"];
	}

	/**
	 * @return array
	 */
	public function getGeneratorOptions() : array{
		return ["preset" => $this->levelData["generatorOptions"]];
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return Chunk|null
	 */
	protected function readChunk(int $chunkX, int $chunkZ) : ?Chunk{
		$index = LevelDB::chunkIndex($chunkX, $chunkZ);

		if(!$this->chunkExists($chunkX, $chunkZ)){
			return null;
		}

		try{
			$subChunks = [];
			$heightMap = [];
			$biomeIds = "";

			for($y = Chunk::MAX_SUBCHUNKS - 1; $y >= 0; --$y){
				if($this->db->get($index . self::TAG_SUBCHUNK_PREFIX . chr($y)) !== false){ //Found subchunk data!
					break;
				}
			}

			if($y <= 0 and ($legacyTerrain = $this->db->get($index . self::TAG_LEGACY_TERRAIN)) !== false){ //didn't find any subchunk data but found old (pre-1.0) data
				$offset = 0;
				$fullIds = substr($legacyTerrain, $offset, 32768);
				$offset += 32768;
				$fullData = substr($legacyTerrain, $offset, 16384);
				$offset += 16384;
				$fullSkyLight = substr($legacyTerrain, $offset, 16384);
				$offset += 16384;
				$fullBlockLight = substr($legacyTerrain, $offset, 16384);
				$offset += 16384;

				for($yy = 0; $yy < 8; ++$yy){
					$subOffset = ($yy << 4);
					$ids = "";
					for($i = 0; $i < 256; ++$i){
						$ids .= substr($fullIds, $subOffset, 16);
						$subOffset += 128;
					}
					$data = "";
					$subOffset = ($yy << 3);
					for($i = 0; $i < 256; ++$i){
						$data .= substr($fullData, $subOffset, 8);
						$subOffset += 64;
					}
					$skyLight = "";
					$subOffset = ($yy << 3);
					for($i = 0; $i < 256; ++$i){
						$skyLight .= substr($fullSkyLight, $subOffset, 8);
						$subOffset += 64;
					}
					$blockLight = "";
					$subOffset = ($yy << 3);
					for($i = 0; $i < 256; ++$i){
						$blockLight .= substr($fullBlockLight, $subOffset, 8);
						$subOffset += 64;
					}
					$subChunks[$yy] = new SubChunk($ids, $data, $skyLight, $blockLight);
				}

				$heightMap = array_values(unpack("C*", substr($legacyTerrain, $offset, 256)));
				$offset += 256;
				$biomeIds = ChunkUtils::convertBiomeColors(array_values(unpack("N*", substr($legacyTerrain, $offset, 1024))));
				$offset += 1024;
			}else{
				for(; $y >= 0; --$y){ //If one subchunk exists, all subchunks below it are also guaranteed to exist.
					$offset = 1; //Skip subchunk version byte
					$subChunkData = $this->db->get($index . self::TAG_SUBCHUNK_PREFIX . chr($y));
					$subChunks[$y] = new SubChunk(
						substr($subChunkData, $offset, 4096), //block ids
						substr($subChunkData, $offset += 4096, 2048), //block meta
						substr($subChunkData, $offset += 2048, 2048), //sky light
						substr($subChunkData, $offset += 2048, 2048) //block light
					);
				}

				if(($data2dLegacy = $this->db->get($index . self::TAG_DATA_2D_LEGACY)) !== false){ //Found old data, convert it to new format
					$heightMap = array_values(unpack("C*", substr($data2dLegacy, 0, 256)));
					$biomeIds = ChunkUtils::convertBiomeColors(array_values(unpack("N*", substr($data2dLegacy, 256, 1024))));
				}elseif(($data2d = $this->db->get($index . self::TAG_DATA_2D)) !== false){
					$heightMap = array_values(unpack("v*", substr($data2d, 0, 512)));
					$biomeIds = substr($data2d, 512, 256);
				}
			}

			$nbt = new NBT(NBT::LITTLE_ENDIAN);

			$entities = [];
			if(($entityData = $this->db->get($index . self::TAG_ENTITY)) !== false and strlen($entityData) > 0){
				$nbt->read($entityData, true);
				$entities = $nbt->getData();
				if(!is_array($entities)){
					$entities = [$entities];
				}
			}

			foreach($entities as $entityNBT){
				if($entityNBT->id instanceof IntTag){
					$entityNBT["id"] &= 0xff;
				}
			}

			$tiles = [];
			if(($tileData = $this->db->get($index . self::TAG_BLOCK_ENTITY)) !== false and strlen($tileData) > 0){
				$nbt->read($tileData, true);
				$tiles = $nbt->getData();
				if(!is_array($tiles)){
					$tiles = [$tiles];
				}
			}

			/*
			$extraData = [];
			if(($extraRawData = $this->db->get($index . self::TAG_EXTRA_DATA)) !== false){
				$stream = new BinaryStream($extraRawData);
				$count = $stream->getLInt(); //TODO: check if the extra data is BE or LE
				for($i = 0; $i < $count; ++$i){
					$key = $stream->getInt();
					$value = $stream->getShort(false);
					$extraData[$key] = $value;
				}
			}*/ //TODO

			$chunk = new Chunk(
				$chunkX,
				$chunkZ,
				$subChunks,
				$entities,
				$tiles,
				$biomeIds,
				$heightMap
			);

			//TODO: tile ticks, biome states (?)

			/*
			$flags = $this->db->get($index . self::ENTRY_FLAGS);
			if($flags === false){
				$flags = "\x03";
			}*/
			// TODO: check this, add flags (?)
			$chunk->setGenerated(true);
			$chunk->setPopulated(true);
			$chunk->setLightPopulated(true);

			return $chunk;
		}catch(\Throwable $t){
			$logger = MainLogger::getLogger();
			$logger->error("LevelDB chunk decode error");
			$logger->logException($t);

			return null;

		}
	}

	/**
	 * @param Chunk $chunk
	 */
	protected function writeChunk(Chunk $chunk) : void{
		$index = LevelDB::chunkIndex($chunk->getX(), $chunk->getZ());
		$this->db->put($index . self::TAG_VERSION, chr(self::CURRENT_STORAGE_VERSION));
		$highestIndex = $chunk->getHighestSubChunkIndex();
		$subChunks = $chunk->getSubChunks();
		for($y = $highestIndex; $y >= 0; --$y){ //Subchunks behave like a stack

			$this->db->put($index . self::TAG_SUBCHUNK_PREFIX . chr($y),
				"\x00" . //Subchunk version byte
				$subChunks[$y]->getBlockIdArray() .
				$subChunks[$y]->getBlockDataArray() .
				$subChunks[$y]->getSkyLightArray() .
				$subChunks[$y]->getBlockLightArray()
			);
		}

		$this->db->put($index . self::TAG_DATA_2D, pack("v*", ...$chunk->getHeightMapArray()) . $chunk->getBiomeIdArray());

		$this->writeTags($chunk->getTiles(), $index . self::TAG_BLOCK_ENTITY);
		$this->writeTags($chunk->getEntities(), $index . self::TAG_ENTITY);

		//TODO: clean up old data
	}

	/**
	 * @param array  $targets
	 * @param string $index
	 */
	private function writeTags(array $targets, string $index){
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$out = [];
		foreach($targets as $target){
			if(!$target->closed){
				$target->saveNBT();
				$out[] = $target->namedtag;
			}
		}

		if(!empty($targets)){
			$nbt->setData($out);
			$this->db->put($index, $nbt->write());
		}else{
			$this->db->delete($index);
		}
	}

	/**
	 * @return \LevelDB
	 */
	public function getDatabase(){
		return $this->db;
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return string
	 */
	public static function chunkIndex(int $chunkX, int $chunkZ) : string{
		return Binary::writeLInt($chunkX) . Binary::writeLInt($chunkZ);
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return bool
	 */
	private function chunkExists(int $chunkX, int $chunkZ) : bool{
		return $this->db->get(LevelDB::chunkIndex($chunkX, $chunkZ) . self::TAG_VERSION) !== false;
	}

	public function close(){
		unset($this->db);
	}
}