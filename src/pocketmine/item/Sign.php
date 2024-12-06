<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Sign extends Item {
	/**
	 * Sign constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::SIGN_POST);
		parent::__construct(self::SIGN, $meta, $count, "Sign");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 16;
	}
}