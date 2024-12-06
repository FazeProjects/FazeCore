<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Redstone extends Item {
	/**
	 * Redstone constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::REDSTONE_WIRE);
		parent::__construct(self::REDSTONE, $meta, $count, "Redstone");
	}

}

