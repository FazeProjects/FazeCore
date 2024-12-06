<?php



namespace pocketmine\item;


class Snowball extends Item {
	/**
	 * Snowball constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SNOWBALL, $meta, $count, "Snowball");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 16;
	}

}