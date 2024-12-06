<?php

namespace pocketmine\item;

use pocketmine\block\Block;

class Bed extends Item {

	const WHITE_BED = 0;
	const ORANGE_BED = 1;
	const MAGENTA_BED = 2;
	const LIGTH_BLUE_BED = 3;
	const YELLOW_BED = 4;
	const LIME_BED = 5;
	const PINK_BED = 6;
	const GRAY_BED = 7;
	const LIGHT_GRAY_BED = 8;
	const CYAN_BED = 9;
	const PURPLE_BED = 10;
	const BLUE_BED = 11;
	const BROWN_BED = 12;
	const GREEN_BED = 13;
	const RED_BED = 14;
	const BLACK_BED = 15;

	/**
	 * Bed constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = self::WHITE_BED, $count = 1){
		$this->block = Block::get(Item::BED_BLOCK, $meta);
		parent::__construct(self::BED, $meta, $count, "Bed"); //TODO: Bed Names
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}
}
