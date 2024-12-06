<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class Carrot extends Food {
	/**
	 * Carrot constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::CARROT_BLOCK);
		parent::__construct(self::CARROT, $meta, $count, "Carrot");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 3;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 4.8;
	}
}
