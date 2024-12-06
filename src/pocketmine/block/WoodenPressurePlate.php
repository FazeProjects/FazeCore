<?php



namespace pocketmine\block;

class WoodenPressurePlate extends PressurePlate {
	protected $id = self::WOODEN_PRESSURE_PLATE;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Wooden Pressure Plate";
	}
}