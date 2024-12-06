<?php



namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\tile\BrewingStand;

class BrewingInventory extends ContainerInventory {
	/**
	 * BrewingInventory constructor.
	 *
	 * @param BrewingStand $tile
	 */
	public function __construct(BrewingStand $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::BREWING_STAND));
	}

	/**
	 * @return BrewingStand
	 */
	public function getHolder(){
		return $this->holder;
	}

	/**
	 * @param Item $item
	 */
	public function setIngredient(Item $item){
		$this->setItem(0, $item);
	}

	/**
	 * @return Item
	 */
	public function getIngredient(){
		return $this->getItem(0);
	}

	/**
	 * @param int  $index
	 * @param Item $before
	 * @param bool $send
	 */
	public function onSlotChange($index, $before, $send){
		parent::onSlotChange($index, $before, $send);

		$this->getHolder()->scheduleUpdate();
		$this->getHolder()->updateSurface();
	}
}