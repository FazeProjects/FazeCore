<?php

/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\level\generator\normal;

use pocketmine\block\Block;
use pocketmine\block\Stone;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\biome\BiomeSelector;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\InvalidGeneratorOptionsException;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\populator\Cave;
use pocketmine\level\generator\populator\GroundCover;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\populator\Populator;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Normal extends Generator {
	const NAME = "Normal";

	/** @var Populator[] */
	protected $populators = [];
	protected $waterHeight = 62;

	/** @var Populator[] */
	protected $generationPopulators = [];
	/** @var Simplex */
	protected $noiseBase;

	/** @var BiomeSelector */
	protected $selector;

	private static $GAUSSIAN_KERNEL = null;
	private static $SMOOTH_SIZE = 2;

	/**
	 * @param array $options
	 *
	 * @throws InvalidGeneratorOptionsException
	 */
	public function __construct(array $options = []){
		if(self::$GAUSSIAN_KERNEL === null){
			self::generateKernel();
		}
	}

	private static function generateKernel(){
		self::$GAUSSIAN_KERNEL = [];

		$bellSize = 1 / self::$SMOOTH_SIZE;
		$bellHeight = 2 * self::$SMOOTH_SIZE;

		for($sx = -self::$SMOOTH_SIZE; $sx <= self::$SMOOTH_SIZE; ++$sx){
			self::$GAUSSIAN_KERNEL[$sx + self::$SMOOTH_SIZE] = [];

			for($sz = -self::$SMOOTH_SIZE; $sz <= self::$SMOOTH_SIZE; ++$sz){
				$bx = $bellSize * $sx;
				$bz = $bellSize * $sz;
				self::$GAUSSIAN_KERNEL[$sx + self::$SMOOTH_SIZE][$sz + self::$SMOOTH_SIZE] = $bellHeight * exp(-($bx * $bx + $bz * $bz) / 2);
			}
		}
	}

	public function getName() : string{
		return self::NAME;
	}

	public function getWaterHeight() : int{
		return $this->waterHeight;
	}

	public function getSettings(){
		return [];
	}

	private function pickBiome($x, $z){
		$hash = $x * 2345803 ^ $z * 9236449 ^ $this->level->getSeed();
		$hash *= $hash + 223;
		$xNoise = $hash >> 20 & 3;
		$zNoise = $hash >> 22 & 3;
		if($xNoise == 3){
			$xNoise = 1;
		}
		if($zNoise == 3){
			$zNoise = 1;
		}

		return $this->selector->pickBiome($x + $xNoise - 1, $z + $zNoise - 1);
	}

	public function init(ChunkManager $level, Random $random){
		parent::init($level, $random);
		$this->random->setSeed($this->level->getSeed());
		$this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 32);
		$this->random->setSeed($this->level->getSeed());
		$this->selector = new class($this->random) extends BiomeSelector{
			protected function lookup(float $temperature, float $rainfall) : int{
			    if($temperature < 0.4) { 
			        if($rainfall < 0.3) {
			            return Biome::MOUNTAINS;
			        } else if($rainfall < 0.5) {
			            return Biome::SMALL_MOUNTAINS;
			        } else if($rainfall < 0.8) {
			            return Biome::ICE_PLAINS;
			        } else {
			            return Biome::TAIGA;
			        }
			    } else if($temperature < 0.8) {
			        if($rainfall < 0.4) {
			            return Biome::PLAINS;
			        } else if($rainfall < 0.5) {
			            if($temperature < 0.5) {
			                return Biome::OCEAN;
			            } else {
			                return Biome::BIRCH_FOREST;
			            }
			        } else if($rainfall < 0.7) {
			            return Biome::RIVER;
			        } else if($rainfall < 0.8) {
			            return Biome::FOREST;
			        } else {
			            return Biome::SWAMP;
			        }
			    } else {
			        return Biome::DESERT;
			    }
			}
		};

		$this->selector->recalculate();

		$cover = new GroundCover();
		$this->generationPopulators[] = $cover;

		$cave = new Cave();
		$this->populators[] = $cave;

		$ores = new Ore();
		$ores->setOreTypes([
			new OreType(Block::get(Block::COAL_ORE), 20, 16, 0, 128),
			new OreType(Block::get(Block::IRON_ORE), 20, 8, 0, 64),
			new OreType(Block::get(Block::REDSTONE_ORE), 8, 7, 0, 16),
			new OreType(Block::get(Block::LAPIS_ORE), 1, 6, 0, 32),
			new OreType(Block::get(Block::GOLD_ORE), 2, 8, 0, 32),
			new OreType(Block::get(Block::DIAMOND_ORE), 1, 7, 0, 16),
			new OreType(Block::get(Block::DIRT), 20, 32, 0, 128),
			new OreType(new Stone(Stone::GRANITE), 20, 32, 0, 128),
			new OreType(new Stone(Stone::DIORITE), 20, 32, 0, 128),
			new OreType(new Stone(Stone::ANDESITE), 20, 32, 0, 128),
			new OreType(Block::get(Block::GRAVEL), 10, 16, 0, 128)
		]);
		$this->populators[] = $ores;
	}

	public function generateChunk($chunkX, $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());

		$noise = $this->noiseBase->getFastNoise3D(16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);

		$chunk = $this->level->getChunk($chunkX, $chunkZ);

		$biomeCache = [];

		for($x = 0; $x < 16; ++$x){
			for($z = 0; $z < 16; ++$z){
				$minSum = 0;
				$maxSum = 0;
				$weightSum = 0;

				$biome = $this->pickBiome($chunkX * 16 + $x, $chunkZ * 16 + $z);
				$chunk->setBiomeId($x, $z, $biome->getId());

				for($sx = -self::$SMOOTH_SIZE; $sx <= self::$SMOOTH_SIZE; ++$sx){
					for($sz = -self::$SMOOTH_SIZE; $sz <= self::$SMOOTH_SIZE; ++$sz){

						$weight = self::$GAUSSIAN_KERNEL[$sx + self::$SMOOTH_SIZE][$sz + self::$SMOOTH_SIZE];

						if($sx === 0 and $sz === 0){
							$adjacent = $biome;
						}else{
							$index = Level::chunkHash($chunkX * 16 + $x + $sx, $chunkZ * 16 + $z + $sz);
							if(isset($biomeCache[$index])){
								$adjacent = $biomeCache[$index];
							}else{
								$biomeCache[$index] = $adjacent = $this->pickBiome($chunkX * 16 + $x + $sx, $chunkZ * 16 + $z + $sz);
							}
						}

						$minSum += ($adjacent->getMinElevation() - 1) * $weight;
						$maxSum += $adjacent->getMaxElevation() * $weight;

						$weightSum += $weight;
					}
				}

				$minSum /= $weightSum;
				$maxSum /= $weightSum;

				$solidLand = false;
				for($y = 127; $y >= 0; --$y){
					if($y === 0){
						$chunk->setBlockId($x, $y, $z, Block::BEDROCK);
						continue;
					}

					// A noiseAdjustment of 1 will guarantee ground, a noiseAdjustment of -1 will guarantee air.
					//$effHeight = min($y - $smoothHeight - $minSum,
					$noiseAdjustment = 2 * (($maxSum - $y) / ($maxSum - $minSum)) - 1;


					// To generate caves, we bring the noiseAdjustment down away from 1.
					$caveLevel = $minSum - 10;
					$distAboveCaveLevel = max(0, $y - $caveLevel); // must be positive

					$noiseAdjustment = min($noiseAdjustment, 0.4 + ($distAboveCaveLevel / 10));
					$noiseValue = $noise[$x][$z][$y] + $noiseAdjustment;

					if($noiseValue > 0){
						$chunk->setBlockId($x, $y, $z, Block::STONE);
						$solidLand = true;
					}elseif($y <= $this->waterHeight && $solidLand == false){
						$chunk->setBlockId($x, $y, $z, Block::STILL_WATER);
					}
				}
			}
		}

		foreach($this->generationPopulators as $populator){
			$populator->populate($this->level, $chunkX, $chunkZ, $this->random);
		}
	}

	public function populateChunk($chunkX, $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		foreach($this->populators as $populator){
			$populator->populate($this->level, $chunkX, $chunkZ, $this->random);
		}

		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		$biome = Biome::getBiome($chunk->getBiomeId(7, 7));
		$biome->populateChunk($this->level, $chunkX, $chunkZ, $this->random);
	}

	public function getSpawn(){
		return new Vector3(127.5, 128, 127.5);
	}

}