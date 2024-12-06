<?php



namespace pocketmine\inventory;

use pocketmine\tile\Beacon;

class BeaconInventory extends ContainerInventory {

	/**
	 * BeaconInventory constructor.
	 *
	 * @param Beacon $tile
	 */
	public function __construct(Beacon $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::BEACON));
	}

	/**
	 * @return InventoryHolder
	 */
	public function getHolder(){
		return $this->holder;
	}
}