<?php



namespace pocketmine\item;

class CookedRabbit extends Food {
	/**
	 * CookedRabbit constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_RABBIT, $meta, $count, "Cooked Rabbit");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 5;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 6;
	}
}
