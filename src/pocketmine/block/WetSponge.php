<?php



namespace pocketmine\block;

class WetSponge extends Solid {

	protected $id = self::WET_SPONGE;

	/**
	 * WetSponge constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 3;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.6;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "Wet Sponge";
	}
}
