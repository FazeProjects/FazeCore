<?php



namespace pocketmine\inventory;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\Player;
use pocketmine\Server;

abstract class BaseInventory implements Inventory {

	/** @var InventoryType */
	protected $type;
	/** @var int */
	protected $maxStackSize = Inventory::MAX_STACK;
	/** @var int */
	protected $size;
	/** @var string */
	protected $name;
	/** @var string */
	protected $title;
	/** @var Item[] */
	protected $slots = [];
	/** @var Player[] */
	protected $viewers = [];
	/** @var InventoryHolder */
	protected $holder;

	/**
	 * @param InventoryHolder $holder
	 * @param InventoryType   $type
	 * @param Item[]          $items
	 * @param int             $overrideSize
	 * @param string          $overrideTitle
	 */
	public function __construct(InventoryHolder $holder, InventoryType $type, array $items = [], $overrideSize = null, $overrideTitle = null){
		$this->holder = $holder;

		$this->type = $type;
		if($overrideSize !== null){
			$this->size = (int) $overrideSize;
		}else{
			$this->size = $this->type->getDefaultSize();
		}

		if($overrideTitle !== null){
			$this->title = $overrideTitle;
		}else{
			$this->title = $this->type->getDefaultTitle();
		}

		$this->name = $this->type->getDefaultTitle();

		$this->setContents($items, false);
	}

	public function __destruct(){
		$this->holder = null;
		$this->slots = [];
	}

	/**
	 * @return int
	 */
	public function getSize(){
		return $this->size;
	}

	/**
	 * @return int
	 */
	public function getHotbarSize(){
		return 0;
	}

	/**
	 * @param $size
	 */
	public function setSize($size){
		$this->size = (int) $size;
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize(){
		return $this->maxStackSize;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * @param int $index
	 *
	 * @return Item
	 */
	public function getItem($index){
		return isset($this->slots[$index]) ? clone $this->slots[$index] : Item::get(Item::AIR, 0, 0);
	}

	/**
	 * @param bool $includeEmpty
	 *
	 * @return Item[]
	 */
	public function getContents(bool $includeEmpty = false) : array{
		$contents = [];
		$air = null;

		foreach($this->slots as $i => $slot){
			if($slot !== null){
				$contents[$i] = clone $slot;
			}elseif($includeEmpty){
				$contents[$i] = $air ?? ($air = Item::get(Item::AIR, 0, 0));
			}
		}

		return $contents;
	}

	/**
	 * @param Item[] $items
	 * @param bool   $send
	 */
	public function setContents(array $items, bool $send = true){
		if(count($items) > $this->size){
			$items = array_slice($items, 0, $this->size, true);
		}

		for($i = 0; $i < $this->size; ++$i){
			if(!isset($items[$i])){
				if(isset($this->slots[$i])){
					$this->clear($i, $send);
				}
			}else{
				if(!$this->setItem($i, $items[$i], $send)){
					$this->clear($i, $send);
				}
			}
		}

		if($send){
			$this->sendContents($this->getViewers());
		}
	}

	/**
	 * Drops the contents of the inventory into the specified Level at the specified position and clears the inventory
	 * contents.
	 *
	 * @param Level   $level
	 * @param Vector3 $position
	 */
	public function dropContents(Level $level, Vector3 $position) : void{
		foreach($this->getContents() as $item){
			$level->dropItem($position, $item);
		}

		$this->clearAll();
	}

	/**
	 * @param int  $index
	 * @param Item $item
	 * @param bool $send
	 *
	 * @return bool
	 */
	public function setItem($index, Item $item, $send = true){
		$item = clone $item;
		if($index < 0 or $index >= $this->size){
			return false;
		}elseif($item->getId() === 0 or $item->getCount() <= 0){
			return $this->clear($index, $send);
		}

		$holder = $this->getHolder();
		if($holder instanceof Entity){
			Server::getInstance()->getPluginManager()->callEvent($ev = new EntityInventoryChangeEvent($holder, $this->getItem($index), $item, $index));
			if($ev->isCancelled()){
				$this->sendSlot($index, $this->getViewers());
				return false;
			}
			$item = $ev->getNewItem();
		}

		$old = $this->getItem($index);
		$this->slots[$index] = clone $item;
		$this->onSlotChange($index, $old, $send);

		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function contains(Item $item){
		$count = max(1, $item->getCount());
		$checkDamage = !$item->hasAnyDamageValue();
		$checkTags = $item->hasCompoundTag();
		foreach($this->getContents() as $i){
			if($item->equals($i, $checkDamage, $checkTags)){
				$count -= $i->getCount();
				if($count <= 0){
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param      $slot
	 * @param Item $item
	 * @param bool $matchCount
	 *
	 * @return bool
	 */
	public function slotContains($slot, Item $item, $matchCount = false){
		if($matchCount){
			return $this->getItem($slot)->equals($item, true, true, true);
		}else{
			return $this->getItem($slot)->equals($item) and $this->getItem($slot)->getCount() >= $item->getCount();
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function all(Item $item){
		$slots = [];
		$checkDamage = !$item->hasAnyDamageValue();
		$checkTags = $item->hasCompoundTag();
		foreach($this->getContents() as $index => $i){
			if($item->equals($i, $checkDamage, $checkTags)){
				$slots[$index] = $i;
			}
		}

		return $slots;
	}

	/**
	 * @param Item $item
	 * @param bool $send
	 */
	public function remove(Item $item, $send = true){
		$checkDamage = !$item->hasAnyDamageValue();
		$checkTags = $item->hasCompoundTag();
		$checkCount = $item->getCount() === null ? false : true;

		foreach($this->getContents() as $index => $i){
			if($item->equals($i, $checkDamage, $checkTags, $checkCount)){
				$this->clear($index, $send);
				break;
			}
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return int|string
	 */
	public function first(Item $item){
		$count = max(1, $item->getCount());
		$checkDamage = !$item->hasAnyDamageValue();
		$checkTags = $item->hasCompoundTag();

		foreach($this->getContents() as $index => $i){
			if($item->equals($i, $checkDamage, $checkTags) and $i->getCount() >= $count){
				return $index;
			}
		}

		return -1;
	}

	/**
	 * @return int
	 */
	public function firstEmpty(){
		for($i = 0; $i < $this->size; ++$i){
			if($this->getItem($i)->getId() === Item::AIR){
				return $i;
			}
		}

		return -1;
	}

	public function isSlotEmpty(int $index) : bool{
		return $this->slots[$index] === null or $this->slots[$index]->isNull();
	}

	/**
	 * @return int
	 */
	public function firstOccupied(){
		for($i = 0; $i < $this->size; $i++){
			if(($item = $this->getItem($i))->getId() !== Item::AIR and $item->getCount() > 0){
				return $i;
			}
		}
		return -1;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function canAddItem(Item $item){
		$item = clone $item;
		for($i = 0; $i < $this->getSize(); ++$i){
			$slot = $this->getItem($i);
			if($item->equals($slot)){
				if(($diff = $slot->getMaxStackSize() - $slot->getCount()) > 0){
					$item->setCount($item->getCount() - $diff);
				}
			}elseif($slot->getId() === Item::AIR){
				$item->setCount($item->getCount() - $this->getMaxStackSize());
			}

			if($item->getCount() <= 0){
				return true;
			}
		}

		return false;
	}

	/**
	 * @param Item ...$slots
	 *
	 * @return Item[]
	 */
	public function addItem(...$slots){
		/** @var Item[] $itemSlots */
		/** @var Item[] $slots */
		$itemSlots = [];
		foreach($slots as $slot){
			if(!($slot instanceof Item)){
				throw new \InvalidArgumentException("Expected Item, got " . gettype($slot));
			}
			if($slot->getId() !== 0 and $slot->getCount() > 0){
				$itemSlots[] = clone $slot;
			}
		}

		$emptySlots = [];

		for($i = 0; $i < $this->getSize(); ++$i){
			$item = $this->getItem($i);
			if($item->getId() === Item::AIR or $item->getCount() <= 0){
				$emptySlots[] = $i;
			}

			foreach($itemSlots as $index => $slot){
				if($slot->equals($item) and $item->getCount() < $item->getMaxStackSize()){
					$amount = min($item->getMaxStackSize() - $item->getCount(), $slot->getCount(), $this->getMaxStackSize());
					if($amount > 0){
						$slot->setCount($slot->getCount() - $amount);
						$item->setCount($item->getCount() + $amount);
						$this->setItem($i, $item);
						if($slot->getCount() <= 0){
							unset($itemSlots[$index]);
						}
					}
				}
			}

			if(count($itemSlots) === 0){
				break;
			}
		}

		if(count($itemSlots) > 0 and count($emptySlots) > 0){
			foreach($emptySlots as $slotIndex){
				//This loop only gets the first item, then goes to the next empty slot
				foreach($itemSlots as $index => $slot){
					$amount = min($slot->getMaxStackSize(), $slot->getCount(), $this->getMaxStackSize());
					$slot->setCount($slot->getCount() - $amount);
					$item = clone $slot;
					$item->setCount($amount);
					$this->setItem($slotIndex, $item);
					if($slot->getCount() <= 0){
						unset($itemSlots[$index]);
					}
					break;
				}
			}
		}

		return $itemSlots;
	}

	/**
	 * @param Item ...$slots
	 *
	 * @return Item[]
	 */
	public function removeItem(...$slots){
		/** @var Item[] $itemSlots */
		/** @var Item[] $slots */
		$itemSlots = [];
		foreach($slots as $slot){
			if(!($slot instanceof Item)){
				throw new \InvalidArgumentException("Expected Item[], got " . gettype($slot));
			}
			if($slot->getId() !== 0 and $slot->getCount() > 0){
				$itemSlots[] = clone $slot;
			}
		}

		for($i = 0; $i < $this->getSize(); ++$i){
			$item = $this->getItem($i);
			if($item->getId() === Item::AIR or $item->getCount() <= 0){
				continue;
			}

			foreach($itemSlots as $index => $slot){
				if($slot->equals($item, !$slot->hasAnyDamageValue(), $slot->hasCompoundTag())){
					$amount = min($item->getCount(), $slot->getCount());
					$slot->setCount($slot->getCount() - $amount);
					$item->setCount($item->getCount() - $amount);
					$this->setItem($i, $item);
					if($slot->getCount() <= 0){
						unset($itemSlots[$index]);
					}
				}
			}

			if(count($itemSlots) === 0){
				break;
			}
		}

		return $itemSlots;
	}

	/**
	 * @param int  $index
	 * @param bool $send
	 *
	 * @return bool
	 */
	public function clear($index, $send = true){
		if(isset($this->slots[$index])){
			$item = Item::get(Item::AIR, 0, 0);
			$old = $this->slots[$index];
			$holder = $this->getHolder();
			if($holder instanceof Entity){
				Server::getInstance()->getPluginManager()->callEvent($ev = new EntityInventoryChangeEvent($holder, $old, $item, $index));
				if($ev->isCancelled()){
					$this->sendSlot($index, $this->getViewers());
					return false;
				}
				$item = $ev->getNewItem();
			}
			if($item->getId() !== Item::AIR){
				$this->slots[$index] = clone $item;
			}else{
				unset($this->slots[$index]);
			}
			$this->onSlotChange($index, $old, $send);
		}

		return true;
	}

	/**
	 * @param bool $send
	 */
	public function clearAll($send = true){
		foreach($this->getContents() as $index => $i){
			$this->clear($index, $send);
		}
	}

	/**
	 * @return Player[]
	 */
	public function getViewers(){
		return $this->viewers;
	}

	/**
	 * Removes the inventory window from all players currently viewing it.
	 *
	 * @param bool $force Force removal of permanent windows such as the player's own inventory. Used internally.
	 */
	public function removeAllViewers(bool $force = false) : void{
		foreach($this->viewers as $hash => $viewer){
			$viewer->removeWindow($this, $force);
			unset($this->viewers[$hash]);
		}
	}

	/**
	 * @return InventoryHolder
	 */
	public function getHolder(){
		return $this->holder;
	}

	/**
	 * @param int $size
	 */
	public function setMaxStackSize($size){
		$this->maxStackSize = (int) $size;
	}

	/**
	 * @param Player $who
	 *
	 * @return bool
	 */
	public function open(Player $who){
		$who->getServer()->getPluginManager()->callEvent($ev = new InventoryOpenEvent($this, $who));
		if($ev->isCancelled()){
			return false;
		}
		$this->onOpen($who);

		return true;
	}

	/**
	 * @param Player $who
	 *
	 * @return mixed|void
	 */
	public function close(Player $who){
		$this->onClose($who);
	}

	/**
	 * @param Player $who
	 */
	public function onOpen(Player $who){
		$this->viewers[spl_object_hash($who)] = $who;
	}

	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		unset($this->viewers[spl_object_hash($who)]);
	}

	/**
	 * @param int  $index
	 * @param Item $before
	 * @param bool $send
	 */
	public function onSlotChange($index, $before, $send){
		if($send){
			$this->sendSlot($index, $this->getViewers());
		}
	}

	/**
	 * @param Transaction $transaction
	 *
	 * @return bool
	 */
	public function processSlotChange(Transaction $transaction) : bool{
		return true;
	}


	/**
	 * @param Player|Player[] $target
	 */
	public function sendContents($target){
		if($target instanceof Player){
			$target = [$target];
		}

		$pk = new ContainerSetContentPacket();
		$pk->slots = [];
		for($i = 0; $i < $this->getSize(); ++$i){
			$pk->slots[$i] = $this->getItem($i);
		}

		foreach($target as $player){
			if(($id = $player->getWindowId($this)) === -1 or $player->spawned !== true){
				$this->close($player);
				continue;
			}
			$pk->windowid = $id;
			$pk->targetEid = $player->getId();
			$player->dataPacket($pk);
		}
	}

	/**
	 * @param int             $index
	 * @param Player|Player[] $target
	 */
	public function sendSlot($index, $target){
		if($target instanceof Player){
			$target = [$target];
		}

		$pk = new ContainerSetSlotPacket();
		$pk->slot = $index;
		$pk->item = clone $this->getItem($index);

		foreach($target as $player){
			if(($id = $player->getWindowId($this)) === -1){
				$this->close($player);
				continue;
			}
			$pk->windowid = $id;
			$player->dataPacket($pk);
		}
	}

	/**
	 * @return InventoryType
	 */
	public function getType(){
		return $this->type;
	}

}