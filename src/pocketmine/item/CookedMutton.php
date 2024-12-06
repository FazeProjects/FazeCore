<?php



namespace pocketmine\item;

class CookedMutton extends Food {
	/**
	 * CookedMutton constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_MUTTON, $meta, $count, "Cooked Mutton");
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
		return 9.6;
	}

}

