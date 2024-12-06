<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;

class EntityInventoryChangeEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	private $oldItem;
	private $newItem;
	private $slot;

	/**
	 * EntityInventoryChangeEvent constructor.
	 *
	 * @param Entity $entity
	 * @param Item   $oldItem
	 * @param Item   $newItem
	 * @param        $slot
	 */
	public function __construct(Entity $entity, Item $oldItem, Item $newItem, $slot){
		$this->entity = $entity;
		$this->oldItem = $oldItem;
		$this->newItem = $newItem;
		$this->slot = (int) $slot;
	}

	/**
	 * @return int
	 */
	public function getSlot(){
		return $this->slot;
	}

	/**
	 * @return Item
	 */
	public function getNewItem(){
		return $this->newItem;
	}

	/**
	 * @param Item $item
	 */
	public function setNewItem(Item $item){
		$this->newItem = $item;
	}

	/**
	 * @return Item
	 */
	public function getOldItem(){
		return $this->oldItem;
	}


}