<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\item\Food;
use pocketmine\item\Item;

class EntityEatItemEvent extends EntityEatEvent {
	/**
	 * EntityEatItemEvent constructor.
	 *
	 * @param Entity $entity
	 * @param Food   $foodSource
	 */
	public function __construct(Entity $entity, Food $foodSource){
		parent::__construct($entity, $foodSource);
	}

	/**
	 * @return Item
	 */
	public function getResidue(){
		return parent::getResidue();
	}

	/**
	 * @param $residue
	 */
	public function setResidue($residue){
		if(!($residue instanceof Item)){
			throw new \InvalidArgumentException("Eating an item can only result in the item's remains.");
		}
		parent::setResidue($residue);
	}
}
