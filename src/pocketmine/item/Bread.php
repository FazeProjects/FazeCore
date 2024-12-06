<?php



namespace pocketmine\item;

class Bread extends Food {
	/**
	 * Bread constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BREAD, $meta, $count, "Bread");
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

