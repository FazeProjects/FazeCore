<?php



namespace pocketmine\item;

class Steak extends Food {
	/**
	 * Steak constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STEAK, $meta, $count, "Steak");
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

