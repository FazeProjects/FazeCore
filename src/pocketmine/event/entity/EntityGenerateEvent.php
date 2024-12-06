<?php



namespace pocketmine\event\entity;


use pocketmine\event\Cancellable;
use pocketmine\level\Position;

class EntityGenerateEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	const CAUSE_AI_HOLDER = 0;
	const CAUSE_MOB_SPAWNER = 1;

	/** @var Position */
	private $position;
	private $cause;
	private $entityType;

	/**
	 * EntityGenerateEvent constructor.
	 *
	 * @param Position $pos
	 * @param int      $entityType
	 * @param int      $cause
	 */
	public function __construct(Position $pos, int $entityType, int $cause = self::CAUSE_MOB_SPAWNER){
		$this->position = $pos;
		$this->entityType = $entityType;
		$this->cause = $cause;
	}

	/**
	 * @return Position
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @param Position $pos
	 */
	public function setPosition(Position $pos){
		$this->position = $pos;
	}

	/**
	 * @return int
	 */
	public function getType() : int{
		return $this->entityType;
	}

	/**
	 * @return int
	 */
	public function getCause() : int{
		return $this->cause;
	}
}