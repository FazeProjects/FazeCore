<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Potato extends Item {
	/**
	 * Potato constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::POTATO_BLOCK);
		parent::__construct(self::POTATO, $meta, $count, "Potato");
	}
}