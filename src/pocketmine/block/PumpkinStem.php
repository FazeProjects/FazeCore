<?php



namespace pocketmine\block;

use pocketmine\event\block\BlockGrowEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Server;

class PumpkinStem extends Crops {

	protected $id = self::PUMPKIN_STEM;

	/**
	 * PumpkinStem constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Pumpkin Stem";
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(Vector3::SIDE_DOWN)->getId() !== Block::FARMLAND){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if(mt_rand(0, 2) === 1){
				if($this->meta < 0x07){
					$block = clone $this;
					++$block->meta;
					Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($this, $block));
					if(!$ev->isCancelled()){
						$this->getLevel()->setBlock($this, $ev->getNewState(), true);
					}

					return Level::BLOCK_UPDATE_RANDOM;
				}else{
					for($side = 2; $side <= 5; ++$side){
						$b = $this->getSide($side);
						if($b->getId() === self::PUMPKIN){
							return Level::BLOCK_UPDATE_RANDOM;
						}
					}
					$side = $this->getSide(mt_rand(2, 5));
					$d = $side->getSide(0);
					if($side->getId() === self::AIR and ($d->getId() === self::FARMLAND or $d->getId() === self::GRASS or $d->getId() === self::DIRT)){
						Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($side, new Pumpkin()));
						if(!$ev->isCancelled()){
							$this->getLevel()->setBlock($side, $ev->getNewState(), true);
						}
					}
				}
			}

			return Level::BLOCK_UPDATE_RANDOM;
		}

		return false;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[Item::PUMPKIN_SEEDS, 0, mt_rand(0, 2)],
		];
	}
}