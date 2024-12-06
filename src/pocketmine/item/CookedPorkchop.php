<?php



namespace pocketmine\item;

class CookedPorkchop extends Food {
	/**
	 * CookedPorkchop constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_PORKCHOP, $meta, $count, "Cooked Porkchop");
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
		return 12.8;
	}
}

