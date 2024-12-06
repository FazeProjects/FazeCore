<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

/**
 * Inventory related events.
 */
class ExplosionPrimeEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	/** @var float */
	protected $force;
	/** @var bool */
	private $blockBreaking;
	/** @var bool */
	private $dropItem;

	public function __construct(Entity $entity, float $force, bool $dropItem){
		$this->entity = $entity;
		$this->force = $force;
		$this->blockBreaking = true;
		$this->dropItem = $dropItem;
	}

	/**
	 * @param bool $dropItem
	 */
	public function setDropItem(bool $dropItem){
		$this->dropItem = $dropItem;
	}

	/**
	 * @return bool
	 */
	public function dropItem() : bool{
		return $this->dropItem;
	}

	/**
	 * @return float
	 */
	public function getForce(){
		return $this->force;
	}

	/**
	 * @param $force
	 */
	public function setForce($force){
		$this->force = (float) $force;
	}

	/**
	 * @return bool
	 */
	public function isBlockBreaking(){
		return $this->blockBreaking;
	}

	/**
	 * @param bool $affectsBlocks
	 */
	public function setBlockBreaking($affectsBlocks){
		$this->blockBreaking = (bool) $affectsBlocks;
	}
}