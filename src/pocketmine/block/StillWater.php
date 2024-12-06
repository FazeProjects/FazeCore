<?php



namespace pocketmine\block;

use pocketmine\level\Level;

class StillWater extends Water {

	protected $id = self::STILL_WATER;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Still Water";
	}
}