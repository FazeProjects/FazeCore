<?php



namespace pocketmine\item;

class GoldenCarrot extends Food {
	/**
	 * GoldenCarrot constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLDEN_CARROT, $meta, $count, "Golden Carrot");
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
		return 14.4;
	}
}
