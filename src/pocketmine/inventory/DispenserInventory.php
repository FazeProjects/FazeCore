<?php



namespace pocketmine\inventory;

use pocketmine\tile\Dispenser;

class DispenserInventory extends ContainerInventory {
	/**
	 * DispenserInventory constructor.
	 *
	 * @param Dispenser $tile
	 */
	public function __construct(Dispenser $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::DISPENSER));
	}

	/**
	 * @return Dispenser
	 */
	public function getHolder(){
		return $this->holder;
	}
}