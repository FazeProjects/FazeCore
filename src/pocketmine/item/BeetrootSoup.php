<?php

namespace pocketmine\item;


class BeetrootSoup extends Food {
	/**
	 * BeetrootSoup constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BEETROOT_SOUP, $meta, $count, "Beetroot Soup");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
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
		return 7.2;
	}

	/**
	 * @return Item
	 */
	public function getResidue(){
		return Item::get(Item::BOWL);
	}


}