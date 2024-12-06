<?php



namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\Player;

class DropItemTransaction extends BaseTransaction {

	const TRANSACTION_TYPE = Transaction::TYPE_DROP_ITEM;

	protected $inventory = null;

	protected $slot = null;

	protected $sourceItem = null;

	/**
	 * @param Item $droppedItem
	 */
	public function __construct(Item $droppedItem){
		$this->targetItem = $droppedItem;
	}

	/**
	 * @param Item $item
	 */
	public function setSourceItem(Item $item){
		//Nothing to update
	}

	/**
	 * @return null
	 */
	public function getInventory(){
		return null;
	}

	/**
	 * @return null
	 */
	public function getSlot(){
		return null;
	}

	/**
	 * @param Player $source
	 */
	public function sendSlotUpdate(Player $source){
		//Nothing to update
	}

	/**
	 * @return array
	 */
	public function getChange(){
		return ["in" => $this->getTargetItem(),
			"out" => null];
	}

	/**
	 * @param Player $source
	 *
	 * @return bool
	 */
	public function execute(Player $source) : bool{
		$droppedItem = $this->getTargetItem();
		if(!$source->getServer()->allowInventoryCheats and !$source->isCreative()){
			if(!$source->getFloatingInventory()->contains($droppedItem)){
				return false;
			}
			$source->getFloatingInventory()->removeItem($droppedItem);
		}
		$source->dropItem($droppedItem);
		return true;
	}
}