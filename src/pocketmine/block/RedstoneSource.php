<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

/*
 * This class is the power of all redstone blocks!
 */

class RedstoneSource extends Flowable {
	protected $maxStrength = 15;
	protected $activated = false;

	/**
	 * RedstoneSource constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getMaxStrength(){
		return $this->maxStrength;
	}

	/**
	 * @param Block|null $from
	 *
	 * @return bool
	 */
	public function isActivated(Block $from = null){
		return $this->activated;
	}

	/**
	 * @return bool
	 */
	public function canCalc(){
		return $this->getLevel()->getServer()->redstoneEnabled;
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool|void
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($this, $this, true);
		if($this->isActivated()){
			$this->activate();
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return mixed|void
	 */
	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
		if($this->isActivated()){
			$this->deactivate();
		}
	}

	/**
	 * @param Block $block
	 */
	public function activateBlockWithoutWire(Block $block){

		if(($block instanceof Door) or ($block instanceof Trapdoor) or ($block instanceof FenceGate)){
			if(!$block->isOpened()) $block->onActivate(new Item(0));
		}
		if($block->getId() == Block::TNT) $block->onActivate(new Item(Item::FLINT_AND_STEEL));
		/** @var InactiveRedstoneLamp $block */
		if($block->getId() == Block::REDSTONE_LAMP) $block->turnOn();
		/** @var Dropper|Dispenser $block */
		if($block->getId() == Block::DROPPER or $block->getId() == Block::DISPENSER) $block->activate();

		/** @var PoweredRepeater $block */
		if($block->getId() == Block::UNPOWERED_REPEATER_BLOCK){
			if($this->equals($block->getSide($block->getDirection()))) $block->activate();
		}
	}

	/**
	 * @param Block $block
	 */
	public function activateBlock(Block $block){
		$this->activateBlockWithoutWire($block);
		if($block->getId() == Block::REDSTONE_WIRE){
			/** @var RedstoneWire $wire */
			$wire = $block;
			$wire->calcSignal($this->maxStrength, RedstoneWire::ON);
		}
	}

	/**
	 * @param Block $block
	 */
	public function deactivateBlock(Block $block){
		$this->deactivateBlockWithoutWire($block);
		if($block->getId() == Block::REDSTONE_WIRE){
			/** @var RedstoneWire $wire */
			$wire = $block;
			$wire->calcSignal(0, RedstoneWire::OFF);
		}
	}

	/**
	 * @param Block $block
	 */
	public function deactivateBlockWithoutWire(Block $block){
		/** @var Door $block */
		if(!$this->checkPower($block)){
			if(($block instanceof Door) or ($block instanceof Trapdoor) or ($block instanceof FenceGate)){
				if($block->isOpened()) $block->onActivate(new Item(0));
			}
			/** @var ActiveRedstoneLamp $block */
			if($block->getId() == Block::LIT_REDSTONE_LAMP) $block->turnOff();
		}
		/** @var PoweredRepeater $block */
		if($block->getId() == Block::POWERED_REPEATER_BLOCK){
			if($this->equals($block->getSide($block->getDirection()))) $block->deactivate();
		}
	}

	/**
	 * @param array $ignore
	 *
	 * @return bool|void
	 */
	public function activate(array $ignore = []){
		if($this->canCalc()){
			$this->activated = true;
			/** @var Door $block */

			$sides = [Vector3::SIDE_EAST, Vector3::SIDE_WEST, Vector3::SIDE_SOUTH, Vector3::SIDE_NORTH, Vector3::SIDE_DOWN];

			foreach($sides as $side){
				if(!in_array($side, $ignore)){
					$block = $this->getSide($side);
					$this->activateBlock($block);
				}
			}
		}
	}

	/**
	 * @param array $ignore
	 *
	 * @return bool|void
	 */
	public function deactivate(array $ignore = []){
		if($this->canCalc()){
			$this->activated = false;
			/** @var Door $block */

			$sides = [Vector3::SIDE_EAST, Vector3::SIDE_WEST, Vector3::SIDE_SOUTH, Vector3::SIDE_NORTH];

			foreach($sides as $side){
				if(!in_array($side, $ignore)){
					$block = $this->getSide($side);
					$this->deactivateBlock($block);
				}
			}

			if(!in_array(Vector3::SIDE_DOWN, $ignore)){
				$block = $this->getSide(Vector3::SIDE_DOWN);
				if(!$this->checkPower($block)){
					/** @var $block ActiveRedstoneLamp */
					if($block->getId() == Block::LIT_REDSTONE_LAMP) $block->turnOff();
				}

				$block = $this->getSide(Vector3::SIDE_DOWN, 2);
				$this->deactivateBlock($block);
			}
		}
	}

	/**
	 * @param Block $block
	 * @param array $ignore
	 * @param bool  $ignoreWire
	 *
	 * @return bool
	 */
	public function checkPower(Block $block, array $ignore = [], $ignoreWire = false){
		if($block instanceof PoweredRepeater){
			if($block->getSide($block->getDirection())->isActivated($block)){
				return true;
			}
			return false;
		}
		$sides = [
			Vector3::SIDE_EAST,
			Vector3::SIDE_WEST,
			Vector3::SIDE_SOUTH,
			Vector3::SIDE_NORTH
		];
		foreach($sides as $side){
			if(!in_array($side, $ignore)){
				$pos = $block->getSide($side);
				if($pos instanceof RedstoneSource){
					if($pos->isActivated($this)){
						if(($ignoreWire and $pos->getId() != self::REDSTONE_WIRE) or (!$ignoreWire and $pos->getId() != self::REDSTONE_WIRE)) return true;
						if(!$ignoreWire and $pos->getId() == self::REDSTONE_WIRE){
							/** @var RedstoneWire $pos */
							$cb = $pos->getUnconnectedSide();
							if(!$cb[0]) return false;
							if($this->equals($pos->getSide($cb[0]))) return true;
						}
					}
				}
			}
		}

		if($block->getId() == Block::LIT_REDSTONE_LAMP and !in_array(Vector3::SIDE_UP, $ignore)){
			$pos = $block->getSide(Vector3::SIDE_UP);
			if($pos instanceof RedstoneSource and $pos->getId() != self::REDSTONE_TORCH){
				if($pos->isActivated($this)) return true;
			}
		}

		return false;
	}


	/**
	 * @param Block $pos
	 * @param array $ignore
	 */
	public function checkTorchOn(Block $pos, array $ignore = []){
		$sides = [Vector3::SIDE_EAST, Vector3::SIDE_WEST, Vector3::SIDE_SOUTH, Vector3::SIDE_NORTH, Vector3::SIDE_UP];
		foreach($sides as $side){
			if(!in_array($side, $ignore)){
				/** @var RedstoneTorch $block */
				$block = $pos->getSide($side);
				if($block->getId() == self::REDSTONE_TORCH){
					$faces = [
						1 => 4,
						2 => 5,
						3 => 2,
						4 => 3,
						5 => 0,
						6 => 0,
						0 => 0,
					];
					if($block->getSide($faces[$block->meta])->equals($pos)){
						$ignoreBlock = $this->getSide(static::getOppositeSide($faces[$block->meta]));
						$block->turnOff(Level::blockHash($ignoreBlock->x, $ignoreBlock->y, $ignoreBlock->z));
					}
				}
			}
		}
	}

	/**
	 * @param Block $pos
	 * @param array $ignore
	 */
	public function checkTorchOff(Block $pos, array $ignore = []){
		$sides = [Vector3::SIDE_EAST, Vector3::SIDE_WEST, Vector3::SIDE_SOUTH, Vector3::SIDE_NORTH, Vector3::SIDE_UP];
		foreach($sides as $side){
			if(!in_array($side, $ignore)){
				/** @var RedstoneTorch $block */
				$block = $pos->getSide($side);
				if($block->getId() == self::UNLIT_REDSTONE_TORCH){
					$faces = [
						1 => 4,
						2 => 5,
						3 => 2,
						4 => 3,
						5 => 0,
						6 => 0,
						0 => 0,
					];
					if($block->getSide($faces[$block->meta])->equals($pos)){
						$ignoreBlock = $this->getSide(static::getOppositeSide($faces[$block->meta]));
						$block->turnOn(Level::blockHash($ignoreBlock->x, $ignoreBlock->y, $ignoreBlock->z));
					}
				}
			}
		}
	}

	/**
	 * @return int
	 */
	public function getStrength(){
		if($this->isActivated()) return $this->maxStrength;
		return 0;
	}
}