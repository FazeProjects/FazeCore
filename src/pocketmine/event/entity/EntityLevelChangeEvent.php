<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\level\Level;

class EntityLevelChangeEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	private $originLevel;
	private $targetLevel;

	/**
	 * EntityLevelChangeEvent constructor.
	 *
	 * @param Entity $entity
	 * @param Level  $originLevel
	 * @param Level  $targetLevel
	 */
	public function __construct(Entity $entity, Level $originLevel, Level $targetLevel){
		$this->entity = $entity;
		$this->originLevel = $originLevel;
		$this->targetLevel = $targetLevel;
	}

	/**
	 * @return Level
	 */
	public function getOrigin(){
		return $this->originLevel;
	}

	/**
	 * @return Level
	 */
	public function getTarget(){
		return $this->targetLevel;
	}
}