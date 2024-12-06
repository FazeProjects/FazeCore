<?php



namespace pocketmine\inventory;

/**
 * Всё, что связано с крафтом.
 */
class CraftingInventory extends BaseInventory {

	private $resultInventory;

	public function __construct(InventoryHolder $holder, Inventory $resultInventory, InventoryType $inventoryType){
		if($inventoryType->getDefaultTitle() !== "Crafting"){
			throw new \InvalidStateException("Invalid Inventory type, expected CRAFTING or WORKBENCH");
		}
		$this->resultInventory = $resultInventory;
		parent::__construct($holder, $inventoryType);
	}

	/**
	 * @return Inventory
	 */
	public function getResultInventory(){
		return $this->resultInventory;
	}

	/**
	 * @return mixed
	 */
	public function getSize(){
		return $this->getResultInventory()->getSize() + parent::getSize();
	}
}