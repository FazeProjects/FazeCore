<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class JungleDoor extends Door {
	/**
	 * JungleDoor constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::JUNGLE_DOOR_BLOCK);
		parent::__construct(self::JUNGLE_DOOR, $meta, $count, "Jungle Door");
	}
}