<?php



namespace pocketmine\item;

class RawPorkchop extends Food{
	/**
	 * RawPorkchop constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_PORKCHOP, $meta, $count, "Raw Porkchop");
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
		return 0.6;
	}

}

