<?php



namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\UUID;

class ShapelessRecipe implements Recipe {
	/** @var Item */
	private $output;

	private $id = null;

	/** @var Item[] */
	private $ingredients = [];

	/**
	 * ShapelessRecipe constructor.
	 *
	 * @param Item $result
	 */
	public function __construct(Item $result){
		$this->output = clone $result;
	}

	/**
	 * @return null
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @param UUID $id
	 */
	public function setId(UUID $id){
		if($this->id !== null){
			throw new \InvalidStateException("Id is already set");
		}

		$this->id = $id;
	}

	/**
	 * @return Item
	 */
	public function getResult(){
		return clone $this->output;
	}

	/**
	 * @param Item $item
	 *
	 * @returns ShapelessRecipe
	 *
	 * @throws \InvalidArgumentException
	 */
	public function addIngredient(Item $item){
		if(count($this->ingredients) >= 9){
			throw new \InvalidArgumentException("Shapeless recipes cannot have more than 9 ingredients");
		}

		while($item->getCount() > 0){
			$this->ingredients[] = $item->pop();
		}

		return $this;
	}

	/**
	 * @param Item $item
	 *
	 * @return $this
	 */
	public function removeIngredient(Item $item){
		foreach($this->ingredients as $index => $ingredient){
			if($item->getCount() <= 0){
				break;
			}
			if($ingredient->equals($item, !$item->hasAnyDamageValue(), $item->hasCompoundTag())){
				unset($this->ingredients[$index]);
				$item->pop();
			}
		}

		return $this;
	}

	/**
	 * @return Item[]
	 */
	public function getIngredientList(){
		$ingredients = [];
		foreach($this->ingredients as $ingredient){
			$ingredients[] = clone $ingredient;
		}

		return $ingredients;
	}

	/**
	 * @return int
	 */
	public function getIngredientCount(){
		$count = 0;
		foreach($this->ingredients as $ingredient){
			$count += $ingredient->getCount();
		}

		return $count;
	}

	public function registerToCraftingManager(){
		Server::getInstance()->getCraftingManager()->registerShapelessRecipe($this);
	}
}