<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerItemHeldEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	private $item;
	private $slot;
	private $inventorySlot;

	/**
	 * PlayerItemHeldEvent constructor.
	 *
	 * @param Player $player
	 * @param Item   $item
	 * @param        $inventorySlot
	 * @param        $slot
	 */
	public function __construct(Player $player, Item $item, $inventorySlot, $slot){
		$this->player = $player;
		$this->item = $item;
		$this->inventorySlot = (int) $inventorySlot;
		$this->slot = (int) $slot;
	}

	/**
	 * @return int
	 */
	public function getSlot(){
		return $this->slot;
	}

	/**
	 * @return int
	 */
	public function getInventorySlot(){
		return $this->inventorySlot;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

}