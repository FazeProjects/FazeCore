<?php

namespace pocketmine\item;

use pocketmine\block\Block;

class BeetrootSeeds extends Item {
	/**
	 * BeetrootSeeds constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::BEETROOT_BLOCK);
		parent::__construct(self::BEETROOT_SEEDS, $meta, $count, "Beetroot Seeds");
	}
}