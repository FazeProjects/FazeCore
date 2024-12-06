<?php



namespace pocketmine\inventory;

use pocketmine\entity\Human;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class EnderChestInventory extends ContainerInventory {

	/** @var Human|Player */
	private $owner;

	/**
	 * EnderChestInventory constructor.
	 *
	 * @param Human $owner
	 * @param null  $contents
	 */
	public function __construct(Human $owner, $contents = null){
		$this->owner = $owner;
		parent::__construct(new FakeBlockMenu($this, $owner->getPosition()), InventoryType::get(InventoryType::ENDER_CHEST));

		if($contents !== null){
			if($contents instanceof ListTag){ //Saved data to be loaded into the inventory
				foreach($contents as $item){
					$this->setItem($item["Slot"], Item::nbtDeserialize($item));
				}
			}else{
				throw new \InvalidArgumentException("Expecting ListTag, received " . gettype($contents));
			}
		}
	}

	/**
	 * @return Human|Player
	 */
	public function getOwner(){
		return $this->owner;
	}

	/**
	 * Set the fake block menu's position to a valid tile position
	 * and send the inventory window to the owner
	 *
	 * @param Position $pos
	 */
	public function openAt(Position $pos){
		$this->getHolder()->setComponents($pos->x, $pos->y, $pos->z);
		$this->getHolder()->setLevel($pos->getLevel());
		$this->owner->addWindow($this);
	}

	/**
	 * @return FakeBlockMenu
	 */
	public function getHolder(){
		return $this->holder;
	}

	/**
	 * @param Player $who
	 */
	public function onOpen(Player $who){
		parent::onOpen($who);

		if(count($this->getViewers()) === 1){
			$pk = new BlockEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 2;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				$level->broadcastLevelSoundEvent($this->getHolder(), LevelSoundEventPacket::SOUND_CHEST_OPEN);
				$level->broadcastLevelSoundEvent($this->getHolder(), LevelSoundEventPacket::SOUND_PORTAL);
				$level->addChunkPacket($this->getHolder()->getFloorX() >> 4, $this->getHolder()->getFloorZ() >> 4, $pk);
			}
		}
	}

	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		if(count($this->getViewers()) === 1){
			$pk = new BlockEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 0;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				$level->broadcastLevelSoundEvent($this->getHolder(), LevelSoundEventPacket::SOUND_CHEST_CLOSED);
				$level->addChunkPacket($this->getHolder()->getFloorX() >> 4, $this->getHolder()->getFloorZ() >> 4, $pk);
			}
		}

		parent::onClose($who);
	}

}