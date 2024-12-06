<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class WheatSeeds extends Item {
	/**
	 * WheatSeeds constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::WHEAT_BLOCK);
		parent::__construct(self::WHEAT_SEEDS, $meta, $count, "Wheat Seeds");
	}
}