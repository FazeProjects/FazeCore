<?php


 
namespace pocketmine\level\particle;

use pocketmine\item\Item;
use pocketmine\math\Vector3;

class ItemBreakParticle extends GenericParticle {
	/**
	 * ItemBreakParticle constructor.
	 *
	 * @param Vector3 $pos
	 * @param Item    $item
	 */
	public function __construct(Vector3 $pos, Item $item){
		parent::__construct($pos, Particle::TYPE_ITEM_BREAK, ($item->getId() << 16) | $item->getDamage());
	}
}
