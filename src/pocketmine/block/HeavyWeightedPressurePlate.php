<?php



namespace pocketmine\block;

class HeavyWeightedPressurePlate extends PressurePlate {
	protected $id = self::HEAVY_WEIGHTED_PRESSURE_PLATE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Heavy Weighted Pressure Plate";
	}
}