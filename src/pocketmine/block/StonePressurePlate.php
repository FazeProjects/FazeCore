<?php



namespace pocketmine\block;

class StonePressurePlate extends PressurePlate {
	protected $id = self::STONE_PRESSURE_PLATE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Stone Pressure Plate";
	}
}