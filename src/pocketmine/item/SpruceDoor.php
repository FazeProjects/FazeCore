<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class SpruceDoor extends Door {
	/**
	 * SpruceDoor constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::SPRUCE_DOOR_BLOCK);
		parent::__construct(self::SPRUCE_DOOR, $meta, $count, "Spruce Door");
	}
}