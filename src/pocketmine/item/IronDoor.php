<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class IronDoor extends Door {
	/**
	 * IronDoor constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::IRON_DOOR_BLOCK);
		parent::__construct(self::IRON_DOOR, $meta, $count, "Iron Door");
	}
}