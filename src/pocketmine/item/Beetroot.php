<?php



namespace pocketmine\item;

class Beetroot extends Food {
	/**
	 * Beetroot constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BEETROOT, $meta, $count, "Beetroot");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 1;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 1.2;
	}
}

