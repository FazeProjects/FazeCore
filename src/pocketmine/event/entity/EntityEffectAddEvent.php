<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityEffectAddEvent extends EntityEvent implements Cancellable {

	public static $handlerList = null;

	/** @var Effect */
	protected $effect;

	/**
	 * EntityEffectAddEvent constructor.
	 *
	 * @param Entity $entity
	 * @param Effect $effect
	 */
	public function __construct(Entity $entity, Effect $effect){
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
