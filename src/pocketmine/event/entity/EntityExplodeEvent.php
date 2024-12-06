<?php



namespace pocketmine\event\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\level\Position;

/**
 * Called when the object explodes
 */
class EntityExplodeEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Position */
	protected $position;

	/**
	 * @var Block[]
	 */
	protected $blocks;

	/** @var float */
	protected $yield;

	protected $arsonist;

	/**
     * @param Block[]  $blocks
	 * @param float    $yield
	 */
	public function __construct(Entity $entity, Position $position, array $blocks, $yield, $arsonist = null){
		$this->entity = $entity;
		$this->position = $position;
		$this->blocks = $blocks;
		$this->yield = $yield;
		$this->arsonist = $arsonist;
	}

	public function getArsonist(){
		return $this->arsonist;
	}

	/**
	 * @return Position
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @return Block[]
	 */
	public function getBlockList(){
		return $this->blocks;
	}

	/**
	 * @param Block[] $blocks
	 */
	public function setBlockList(array $blocks){
		$this->blocks = $blocks;
	}

	/**
	 * @return float
	 */
	public function getYield(){
		return $this->yield;
	}

	/**
	 * @param float $yield
	 */
	public function setYield($yield){
		$this->yield = $yield;
	}

}