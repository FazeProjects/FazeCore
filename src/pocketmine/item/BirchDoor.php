<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class BirchDoor extends Door {
	/**
	 * BirchDoor constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::BIRCH_DOOR_BLOCK);
		parent::__construct(self::BIRCH_DOOR, $meta, $count, "Birch Door");
	}
}