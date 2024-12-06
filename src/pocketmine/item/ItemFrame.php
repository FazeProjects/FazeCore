<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class ItemFrame extends Item {
	/**
	 * ItemFrame constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::ITEM_FRAME_BLOCK);
		parent::__construct(self::ITEM_FRAME, $meta, $count, "Item Frame");
	}
}