<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class DiamondOre extends Solid {

	protected $id = self::DIAMOND_ORE;

	/**
	 * DiamondOre constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 3;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Diamond Ore";
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
		if($item->isPickaxe() >= 4){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::DIAMOND_ORE, 0, 1],
				];
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				$times = [1, 1, 2, 3, 4];
				$time = $times[mt_rand(0, $fortunel + 1)];
				return [
					[Item::DIAMOND, 0, $time],
				];
			}

		}else{
			return [];
		}
	}
}
