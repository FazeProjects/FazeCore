<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;

/**
 * Called when an entity takes damage from an entity that took damage from another entity, such as being hit by a snowball thrown by a player.
 */
class EntityDamageByChildEntityEvent extends EntityDamageByEntityEvent {
    /** @var int */
    private $childEntityEid;

    public function __construct(Entity $damager, Entity $childEntity, Entity $entity, int $cause, $damage){
        $this->childEntityEid = $childEntity->getId();
        parent::__construct($damager, $entity, $cause, $damage);
    }

    /**
     * Returns the entity that caused the damage, or null if the entity was killed or capped.
     */
    public function getChild(){
        return $this->getEntity()->getLevel()->getServer()->findEntity($this->childEntityEid);
    }
}