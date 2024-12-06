<?php



namespace pocketmine\item;

class CookedChicken extends Food {
	/**
	 * CookedChicken constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_CHICKEN, $meta, $count, "Cooked Chicken");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 6;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 7.2;
	}
}

