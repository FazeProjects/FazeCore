<?php


 
namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\item\FoodSource;

class EntityEatBlockEvent extends EntityEatEvent implements Cancellable {
	/**
	 * EntityEatBlockEvent constructor.
	 *
	 * @param Entity     $entity
	 * @param FoodSource $foodSource
	 */
	public function __construct(Entity $entity, FoodSource $foodSource){
		if(!($foodSource instanceof Block)){
            throw new \InvalidArgumentException("Food source must be a block");
		}
		parent::__construct($entity, $foodSource);
	}

	/**
	 * @return Block
	 */
	public function getResidue(){
		return parent::getResidue();
	}

	/**
	 * @param $residue
	 */
	public function setResidue($residue){
		if(!($residue instanceof Block)){
            throw new \InvalidArgumentException("Eating a block can only result in the remainder of the block");
		}
		parent::setResidue($residue);
	}
}
