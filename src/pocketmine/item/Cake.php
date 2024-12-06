<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Cake extends Item {
	/**
	 * Cake constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::CAKE_BLOCK);
		parent::__construct(self::CAKE, $meta, $count, "Cake");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}
}