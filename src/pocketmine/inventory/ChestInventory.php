<?php



namespace pocketmine\inventory;

use pocketmine\block\TrappedChest;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\tile\Chest;

class ChestInventory extends ContainerInventory {
	/**
	 * ChestInventory constructor.
	 *
	 * @param Chest $tile
	 */
	public function __construct(Chest $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::CHEST));
	}

	/**
	 * @return Chest
	 */
	public function getHolder(){
		return $this->holder;
	}

	/**
	 * @param bool $withAir
	 *
	 * @return array|\pocketmine\item\Item[]
	 */
	public function getContents(bool $includeEmpty = false) : array{
		if($includeEmpty){
			$contents = [];
			for($i = 0; $i < $this->getSize(); ++$i){
				$contents[$i] = $this->getItem($i);
			}

			return $contents;
		}
		return parent::getContents();
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
				$level->addChunkPacket($this->getHolder()->getFloorX() >> 4, $this->getHolder()->getFloorZ() >> 4, $pk);
			}
		}

		if($this->getHolder()->getLevel() instanceof Level){
			/** @var TrappedChest $block */
			$block = $this->getHolder()->getBlock();
			if($block instanceof TrappedChest){
				if(!$block->isActivated()){
					$block->activate();
				}
			}
		}
	}

	/**
	 * @param Player $who
	 */
	public function onClose(Player $who){
		if($this->getHolder()->getLevel() instanceof Level){
			/** @var TrappedChest $block */
			$block = $this->getHolder()->getBlock();
			if($block instanceof TrappedChest){
				if($block->isActivated()){
					$block->deactivate();
				}
			}
		}

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