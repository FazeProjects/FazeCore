<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Skull extends Item {
	const SKELETON = 0;
	const WITHER_SKELETON = 1;
	const ZOMBIE = 2;
	const STEVE = 3;
	const CREEPER = 4;

	/**
	 * Skull constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Block::SKULL_BLOCK);
		parent::__construct(self::SKULL, $meta, $count, "Skull");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 64;
	}

}