<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Cauldron extends Item {
	/**
	 * Cauldron constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Block::CAULDRON_BLOCK);
		parent::__construct(self::CAULDRON, $meta, $count, "Cauldron");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}
}