<?php



namespace pocketmine\block;


class GlowingObsidian extends Solid implements SolidLight {

	protected $id = self::GLOWING_OBSIDIAN;

	/**
	 * GlowingObsidian constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Glowing Obsidian";
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 12;
	}

	public function getHardness() : float{
		return 10;
	}

	public function getResistance(){
		return 50;
	}
}