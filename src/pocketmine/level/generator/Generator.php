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

/**
 * Noise classes used in Levels
 */

namespace pocketmine\level\generator;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\utils\Utils;
use function preg_match;

abstract class Generator{

	/**
	 * Converts a string level seed into an integer for use by the generator.
	 *
	 * @param string $seed
	 *
	 * @return int|null
	 */
	public static function convertSeed(string $seed) : ?int{
		if($seed === ""){ //empty seed should cause a random seed to be selected - can't use 0 here because 0 is a valid seed
			$convertedSeed = null;
		}elseif(preg_match('/^-?\d+$/', $seed) === 1){ //this avoids treating seeds like "404.4" as integer seeds
			$convertedSeed = (int) $seed;
		}else{
			$convertedSeed = Utils::javaStringHash($seed);
		}

		return $convertedSeed;
	}

	/**
	 * @return int
	 */
	public function getWaterHeight() : int{
		return 0;
	}
	
	/** @var ChunkManager */
	protected $level;
	/** @var Random */
	protected $random;

	/**
	 * @param array $settings
	 *
	 * @throws InvalidGeneratorOptionsException
	 */
	public abstract function __construct(array $settings = []);

	public function init(ChunkManager $level, Random $random){
		$this->level = $level;
		$this->random = $random;
	}

	/**
	 * @param $chunkX
	 * @param $chunkZ
	 *
	 * @return mixed
	 */
	public abstract function generateChunk($chunkX, $chunkZ);

	/**
	 * @param $chunkX
	 * @param $chunkZ
	 *
	 * @return mixed
	 */
	public abstract function populateChunk($chunkX, $chunkZ);

	public abstract function getSettings();

	public abstract function getName();

	public abstract function getSpawn();
}
