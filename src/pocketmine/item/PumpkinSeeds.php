<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class PumpkinSeeds extends Item {
	/**
	 * PumpkinSeeds constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::PUMPKIN_STEM);
		parent::__construct(self::PUMPKIN_SEEDS, $meta, $count, "Pumpkin Seeds");
	}
}