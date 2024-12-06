<?php



namespace pocketmine\item;

class RawBeef extends Food {
	/**
	 * RawBeef constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_BEEF, $meta, $count, "Raw Beef");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 3;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 1.8;
	}
}

