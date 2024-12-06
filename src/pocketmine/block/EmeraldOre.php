<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class EmeraldOre extends Solid {

	protected $id = self::EMERALD_ORE;

	/**
	 * EmeraldOre constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Emerald Ore";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 3;
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
					[Item::EMERALD_ORE, 0, 1],
				];
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				$times = [1, 1, 2, 3, 4];
				$time = $times[mt_rand(0, $fortunel + 1)];
				return [
					[Item::EMERALD, 0, $time],
				];
			}
		}else{
			return [];
		}
	}
}
