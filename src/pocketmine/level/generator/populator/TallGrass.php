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

namespace pocketmine\level\generator\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

class TallGrass extends Populator {
	private $randomAmount = 1;
	private $baseAmount = 0;

	/**
	 * @param $amount
	 */
	public function setRandomAmount($amount){
		$this->randomAmount = $amount;
	}

	/**
	 * @param $amount
	 */
	public function setBaseAmount($amount){
		$this->baseAmount = $amount;
	}

	/**
	 * @param ChunkManager $level
	 * @param              $chunkX
	 * @param              $chunkZ
	 * @param Random       $random
	 *
	 * @return mixed|void
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$amount = $random->nextRange(0, $this->randomAmount) + $this->baseAmount;
		for($i = 0; $i < $amount; ++$i){
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($level, $x, $z);

			if($y !== -1 and $this->canTallGrassStay($level, $x, $y, $z)){
				$level->setBlockIdAt($x, $y, $z, Block::TALL_GRASS);
				$level->setBlockDataAt($x, $y, $z, 1);
			}
		}
	}

	private function canTallGrassStay(ChunkManager $level, int $x, int $y, int $z) : bool{
		$b = $level->getBlockIdAt($x, $y, $z);
		return ($b === Block::AIR or $b === Block::SNOW_LAYER) and $level->getBlockIdAt($x, $y - 1, $z) === Block::GRASS;
	}

	private function getHighestWorkableBlock(ChunkManager $level, int $x, int $z) : int{
		for($y = 127; $y >= 0; --$y){
			$b = $level->getBlockIdAt($x, $y, $z);
			if($b !== Block::AIR and $b !== Block::LEAVES and $b !== Block::LEAVES2 and $b !== Block::SNOW_LAYER){
				return $y + 1;
			}
		}

		return -1;
	}
}