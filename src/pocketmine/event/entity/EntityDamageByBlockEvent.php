<?php



namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;

/**
 * Called when an entity takes damage from a block.
 */
class EntityDamageByBlockEvent extends EntityDamageEvent {

	/** @var Block */
	private $damager;


	/**
	 * @param Block         $damager
	 * @param Entity        $entity
	 * @param int           $cause
	 * @param float|float[] $damage
	 */
	public function __construct(Block $damager, Entity $entity, int $cause, $damage){
		$this->damager = $damager;
		parent::__construct($entity, $cause, $damage);
	}

	/**
	 * @return Block
	 */
	public function getDamager(){
		return $this->damager;
	}


}