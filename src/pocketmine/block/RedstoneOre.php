<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\Level;

class RedstoneOre extends Solid {

	protected $id = self::REDSTONE_ORE;

	/**
	 * RedstoneOre constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Redstone Ore";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 3;
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL or $type === Level::BLOCK_UPDATE_TOUCH){
			$this->getLevel()->setBlock($this, Block::get(Item::GLOWING_REDSTONE_ORE, $this->meta));

			return Level::BLOCK_UPDATE_WEAK;
		}

		return false;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_IRON){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::REDSTONE_ORE, 0, 1],
				];
			}else{
				$fortuneL = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortuneL = $fortuneL > 3 ? 3 : $fortuneL;
				return [
					[Item::REDSTONE_DUST, 0, mt_rand(4, 5 + $fortuneL)],
				];
			}
		}else{
			return [];
		}
	}
}
