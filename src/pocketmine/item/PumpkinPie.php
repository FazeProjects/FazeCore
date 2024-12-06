<?php



namespace pocketmine\item;

class PumpkinPie extends Food {
	/**
	 * PumpkinPie constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PUMPKIN_PIE, $meta, $count, "Pumpkin Pie");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 8;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 4.8;
	}
}

