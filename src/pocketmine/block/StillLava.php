<?php



namespace pocketmine\block;

use pocketmine\level\Level;

class StillLava extends Lava {

	protected $id = self::STILL_LAVA;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Still Lava";
	}

}