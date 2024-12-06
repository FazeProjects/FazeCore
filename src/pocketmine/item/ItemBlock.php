<?php



namespace pocketmine\item;

use pocketmine\block\Block;

/**
 * Class used for Items that can be Blocks
 */
class ItemBlock extends Item{
	/**
	 * ItemBlock constructor.
	 *
	 * @param Block $block
	 * @param int   $meta
	 * @param int   $count
	 */
	public function __construct(Block $block, $meta = 0, int $count = 1){
		$this->block = $block;
		parent::__construct($block->getId(), $block->getDamage(), $count, $block->getName());
	}

	public function setDamage(int $meta){
		$this->block->setDamage($meta !== -1 ? $meta & 0xf : 0);
		return parent::setDamage($meta);
	}

	/**
	 * @return Block
	 */
	public function getBlock() : Block{
		return $this->block;
	}

}