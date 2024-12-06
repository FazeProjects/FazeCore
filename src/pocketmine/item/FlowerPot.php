<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class FlowerPot extends Item {
	/**
	 * FlowerPot constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::FLOWER_POT_BLOCK);
		parent::__construct(self::FLOWER_POT, $meta, $count, "Flower Pot");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 64;
	}
} 
