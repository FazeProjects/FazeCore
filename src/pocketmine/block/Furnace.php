<?php



namespace pocketmine\block;


class Furnace extends BurningFurnace {

	protected $id = self::FURNACE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Furnace";
	}

	public function getLightLevel(){
		return 0;
	}
}