<?php



namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\UUID;

class BrewingRecipe implements Recipe {

	private $id = null;

	/** @var Item */
	private $output;

	/** @var Item */
	private $ingredient;

	/** @var Item */
	private $potion;

	/**
	 * BrewingRecipe constructor.
	 *
	 * @param Item $result
	 * @param Item $ingredient
	 * @param Item $potion
	 */
	public function __construct(Item $result, Item $ingredient, Item $potion){
		$this->output = clone $result;
		$this->ingredient = clone $ingredient;
		$this->potion = clone $potion;
	}

	/**
	 * @return Item
	 */
	public function getPotion(){
		return clone $this->potion;
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
	 * @param Item $item
	 */
	public function setInput(Item $item){
		$this->ingredient = clone $item;
	}

	/**
	 * @return Item
	 */
	public function getInput(){
		return clone $this->ingredient;
	}

	/**
	 * @return Item
	 */
	public function getResult(){
		return clone $this->output;
	}

	public function registerToCraftingManager(){
		Server::getInstance()->getCraftingManager()->registerBrewingRecipe($this);
	}
}