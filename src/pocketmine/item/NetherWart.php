<?php



namespace pocketmine\item;

use pocketmine\block\Block;

class NetherWart extends Item {
	/**
	 * NetherWart constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::NETHER_WART_BLOCK);
		parent::__construct(self::NETHER_WART, $meta, $count, "Nether Wart");
	}
}
