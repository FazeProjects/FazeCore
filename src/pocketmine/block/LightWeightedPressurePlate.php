<?php



namespace pocketmine\block;

class LightWeightedPressurePlate extends PressurePlate {
	protected $id = self::LIGHT_WEIGHTED_PRESSURE_PLATE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Light Weighted Pressure Plate";
	}
}