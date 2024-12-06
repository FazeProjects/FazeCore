<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class DoubleRedSandstoneSlab extends DoubleSlab {

	protected $id = Block::DOUBLE_RED_SANDSTONE_SLAB;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Double Red Sandstone Slab";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[Item::RED_SANDSTONE_SLAB, $this->meta, 2],
			];
		}else{
			return [];
		}
	}
}