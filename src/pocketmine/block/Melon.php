<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Melon extends Transparent {

	protected $id = self::MELON_BLOCK;

	/**
	 * Melon constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Melon Block";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 1;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::MELON_BLOCK, 0, 1],
			];
		}else{
			$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
			$fortunel = $fortunel > 2 ? 2 : $fortunel; //Note: for Melon level 2 is the same 3 So highest is 2
			return [
				[Item::MELON_SLICE, 0, mt_rand(3, 7 + $fortunel)],
			];
		}
	}
}
