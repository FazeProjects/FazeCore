<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Sugarcane extends Item {
	/**
	 * Sugarcane constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::SUGARCANE_BLOCK);
		parent::__construct(self::SUGARCANE, $meta, $count, "Sugar Cane");
	}
}