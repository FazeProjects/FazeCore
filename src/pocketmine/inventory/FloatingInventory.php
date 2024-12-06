<?php



namespace pocketmine\inventory;

/**
 * The in-between inventory where items involved in transactions are stored temporarily
 */
class FloatingInventory extends BaseInventory {

	/**
	 * @param InventoryHolder $holder
	 */
	public function __construct(InventoryHolder $holder){
		parent::__construct($holder, InventoryType::get(InventoryType::PLAYER_FLOATING));
	}
}