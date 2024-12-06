<?php



namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityEatItemEvent;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\Player;

abstract class Food extends Item implements FoodSource {
	/**
	 * @return bool
	 */
	public function canBeConsumed() : bool{
		return true;
	}

	public function requiresHunger() : bool{
		return true;
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canBeConsumedBy(Entity $entity) : bool{
		if (!$this->requiresHunger()) {
			return $entity instanceof Player;
		}
		return $entity instanceof Player and ($entity->getFood() < $entity->getMaxFood()) and $this->canBeConsumed();
	}

	/**
	 * @return Food|Item
	 */
	public function getResidue(){
		if($this->getCount() === 1){
			return Item::get(0);
		}else{
			$new = clone $this;
			$new->pop();
			return $new;
		}
	}

	/**
	 * @return array
	 */
	public function getAdditionalEffects() : array{
		return [];
	}

	/**
	 * @param Entity $human
	 */
	public function onConsume(Entity $human){
		$pk = new EntityEventPacket();
		$pk->eid = $human->getId();
		$pk->event = EntityEventPacket::USE_ITEM;
		if($human instanceof Player){
			$human->dataPacket($pk);
		}
		$human->getLevel()->getServer()->broadcastPacket($human->getViewers(), $pk);

		$human->getLevel()->getServer()->getPluginManager()->callEvent($ev = new EntityEatItemEvent($human, $this));

		$human->addSaturation($ev->getSaturationRestore());
		$human->addFood($ev->getFoodRestore());
		foreach($ev->getAdditionalEffects() as $effect){
			$human->addEffect($effect);
		}

		$human->getInventory()->setItemInHand($ev->getResidue());
	}
}
