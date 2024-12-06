<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Hopper extends Item {
	/**
	 * Hopper constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Block::HOPPER_BLOCK);
		parent::__construct(self::HOPPER, $meta, $count, "Hopper");
	}
}