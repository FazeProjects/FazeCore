<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class DarkOakDoor extends Door {
	/**
	 * DarkOakDoor constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::DARK_OAK_DOOR_BLOCK);
		parent::__construct(self::DARK_OAK_DOOR, $meta, $count, "Dark Oak Door");
	}
}