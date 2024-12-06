<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\item\FoodSource;

class EntityEatEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	/** @var FoodSource */
	private $foodSource;
	/** @var int */
	private $foodRestore;
	/** @var float */
	private $saturationRestore;
	private $residue;
	/** @var Effect[] */
	private $additionalEffects;

	/**
	 * EntityEatEvent constructor.
	 *
	 * @param Entity     $entity
	 * @param FoodSource $foodSource
	 */
	public function __construct(Entity $entity, FoodSource $foodSource){
		$this->entity = $entity;
		$this->foodSource = $foodSource;
		$this->foodRestore = $foodSource->getFoodRestore();
		$this->saturationRestore = $foodSource->getSaturationRestore();
		$this->residue = $foodSource->getResidue();
		$this->additionalEffects = $foodSource->getAdditionalEffects();
	}

	/**
	 * @return FoodSource
	 */
	public function getFoodSource(){
		return $this->foodSource;
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return $this->foodRestore;
	}

	/**
	 * @param int $foodRestore
	 */
	public function setFoodRestore(int $foodRestore){
		$this->foodRestore = $foodRestore;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return $this->saturationRestore;
	}

	/**
	 * @param float $saturationRestore
	 */
	public function setSaturationRestore(float $saturationRestore){
		$this->saturationRestore = $saturationRestore;
	}

	/**
	 * @return mixed
	 */
	public function getResidue(){
		return $this->residue;
	}

	/**
	 * @param $residue
	 */
	public function setResidue($residue){
		$this->residue = $residue;
	}

	/**
	 * @return Effect[]
	 */
	public function getAdditionalEffects(){
		return $this->additionalEffects;
	}

	/**
	 * @param Effect[] $additionalEffects
	 *
	 * @throws \TypeError
	 */
	public function setAdditionalEffects(array $additionalEffects){
		foreach($additionalEffects as $effect){
			if(!($effect instanceof Effect)){
				throw new \TypeError("Argument 1 passed to EntityEatEvent::setAdditionalEffects() must be an array of effects.");
			}
		}
		$this->additionalEffects = $additionalEffects;
	}
}
