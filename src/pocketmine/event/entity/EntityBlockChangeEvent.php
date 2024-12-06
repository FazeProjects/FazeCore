<?php



namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

/**
 * Called when an Entity, excluding players, directly changes a block
 */
class EntityBlockChangeEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	private $from;
	private $to;

	/**
	 * EntityBlockChangeEvent constructor.
	 *
	 * @param Entity $entity
	 * @param Block  $from
	 * @param Block  $to
	 */
	public function __construct(Entity $entity, Block $from, Block $to){
		$this->entity = $entity;
		$this->from = $from;
		$this->to = $to;
	}

	/**
	 * @return Block
	 */
	public function getBlock(){
		return $this->from;
	}

	/**
	 * @return Block
	 */
	public function getTo(){
		return $this->to;
	}

}