<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityEffectRemoveEvent extends EntityEvent implements Cancellable{

	public static $handlerList = null;

	/** @var Effect */
	protected $effect;

	/**
	 * EntityEffectRemoveEvent constructor.
	 *
	 * @param Entity $entity
	 * @param int    $effect
	 */
	public function __construct(Entity $entity, int $effect){
		$this->entity = $entity;
		$this->effect = $effect;
	}

	/**
	 * @return Effect
	 */
	public function getEffect(){
		return $this->effect;
	}
}
