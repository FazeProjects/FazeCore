<?php



namespace pocketmine\item;

class Cookie extends Food {
	/**
	 * Cookie constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKIE, $meta, $count, "Cookie");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 2;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 0.4;
	}
}

