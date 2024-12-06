<?php



namespace pocketmine\inventory;

use pocketmine\tile\Hopper;

class HopperInventory extends ContainerInventory {
	/**
	 * HopperInventory constructor.
	 *
	 * @param Hopper $tile
	 */
	public function __construct(Hopper $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::HOPPER));
	}

	/**
	 * @return Hopper
	 */
	public function getHolder(){
		return $this->holder;
	}
}