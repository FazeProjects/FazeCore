<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class MelonSeeds extends Item {
	/**
	 * MelonSeeds constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::MELON_STEM);
		parent::__construct(self::MELON_SEEDS, $meta, $count, "Melon Seeds");
	}
}