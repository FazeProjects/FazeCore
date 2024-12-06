<?php



namespace pocketmine\block;

class IronTrapdoor extends Trapdoor {
	protected $id = self::IRON_TRAPDOOR;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Iron Trapdoor";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 5;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 25;
	}

}