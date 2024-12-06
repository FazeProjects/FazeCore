<?php



namespace pocketmine\inventory;

use pocketmine\network\mcpe\protocol\types\InventoryNetworkIds;

class InventoryType {

	const CHEST = 0;
	const DOUBLE_CHEST = 1;
	const PLAYER = 2;
	const FURNACE = 3;
	const CRAFTING = 4;
	const WORKBENCH = 5;
	//const STONECUTTER = 6;
	const BREWING_STAND = 7;
	const ANVIL = 8;
	const ENCHANT_TABLE = 9;
	const DISPENSER = 10;
	const DROPPER = 11;
	const HOPPER = 12;
	const ENDER_CHEST = 13;
	const BEACON = 14;
	const SHULKER_BOX = 15;

	const PLAYER_FLOATING = 254;

	private static $default = [];

	private $size;
	private $title;
	private $typeId;

	/**
	 * @param $index
	 *
	 * @return InventoryType
	 */
	public static function get($index){
		return isset(static::$default[$index]) ? static::$default[$index] : null;
	}

	public static function init(){
		if(count(static::$default) > 0){
			return;
		}

		//TODO: move network stuff out of here
		//TODO: move inventory data to json
		static::$default = [
			static::CHEST => new InventoryType(27, "Chest", InventoryNetworkIds::CONTAINER),
			static::DOUBLE_CHEST => new InventoryType(27 + 27, "Double Chest", InventoryNetworkIds::CONTAINER),
			static::PLAYER => new InventoryType(36 + 4, "Player", InventoryNetworkIds::INVENTORY), //36 CONTAINER, 4 ARMOR
			static::CRAFTING => new InventoryType(5, "Crafting", InventoryNetworkIds::INVENTORY), //yes, the use of INVENTORY is intended! 4 CRAFTING slots, 1 RESULT
			static::WORKBENCH => new InventoryType(10, "Crafting", InventoryNetworkIds::WORKBENCH), //9 CRAFTING slots, 1 RESULT
			static::FURNACE => new InventoryType(3, "Furnace", InventoryNetworkIds::FURNACE), //2 INPUT, 1 OUTPUT
			static::ENCHANT_TABLE => new InventoryType(2, "Enchant", InventoryNetworkIds::ENCHANTMENT), //1 INPUT/OUTPUT, 1 LAPIS
			static::BREWING_STAND => new InventoryType(4, "Brewing", InventoryNetworkIds::BREWING_STAND), //1 INPUT, 3 POTION
			static::ANVIL => new InventoryType(3, "Anvil", InventoryNetworkIds::ANVIL), //2 INPUT, 1 OUTPUT
			static::DISPENSER => new InventoryType(9, "Dispenser", InventoryNetworkIds::DISPENSER), //9 CONTAINER
			static::DROPPER => new InventoryType(9, "Dropper", InventoryNetworkIds::DROPPER), //9 CONTAINER
			static::HOPPER => new InventoryType(5, "Hopper", InventoryNetworkIds::HOPPER), //5 CONTAINER
			static::ENDER_CHEST => new InventoryType(27, "Ender Chest", InventoryNetworkIds::CONTAINER),
			static::BEACON => new InventoryType(0, "Beacon", InventoryNetworkIds::BEACON), //信标
			static::SHULKER_BOX => new InventoryType(27, "ShulkerBox", InventoryNetworkIds::CONTAINER),

			static::PLAYER_FLOATING => new InventoryType(36, "Floating", null) //Mirror all slots of main inventory (needed for large item pickups)
		];
	}

	/**
	 * @param int    $defaultSize
	 * @param string $defaultTitle
	 * @param int    $typeId
	 */
	private function __construct($defaultSize, $defaultTitle, $typeId = 0){
		$this->size = $defaultSize;
		$this->title = $defaultTitle;
		$this->typeId = $typeId;
	}

	/**
	 * @return int
	 */
	public function getDefaultSize(){
		return $this->size;
	}

	/**
	 * @return string
	 */
	public function getDefaultTitle(){
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getNetworkType(){
		return $this->typeId;
	}
}