<?php



namespace pocketmine\block;

use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Server;


class Mycelium extends Solid {

	protected $id = self::MYCELIUM;

	/**
	 * Mycelium constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Mycelium";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.6;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::MYCELIUM, 0, 1],
			];
		}else{
			return [
				[Item::DIRT, 0, 1],
			];
		}
	}

	/**
	 * @param int $type
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_RANDOM){
			//TODO: light levels
			$x = mt_rand($this->x - 1, $this->x + 1);
			$y = mt_rand($this->y - 2, $this->y + 2);
			$z = mt_rand($this->z - 1, $this->z + 1);
			$block = $this->getLevel()->getBlockAt($x, $y, $z);
			if($block->getId() === Block::DIRT){
				if($block->getSide(1) instanceof Transparent){
					Server::getInstance()->getPluginManager()->callEvent($ev = new BlockSpreadEvent($block, $this, new Mycelium()));
					if(!$ev->isCancelled()){
						$this->getLevel()->setBlock($block, $ev->getNewState());
					}
				}
			}
		}
	}
}
