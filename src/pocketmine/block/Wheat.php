<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class Wheat extends Crops {

	protected $id = self::WHEAT_BLOCK;

	/**
	 * Wheat constructor.
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
		return "Wheat Block";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		$drops = [];
		if($this->meta >= 0x07){
			$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
			$fortunel = $fortunel > 3 ? 3 : $fortunel;
			$drops[] = [Item::WHEAT, 0, 1];
			$drops[] = [Item::WHEAT_SEEDS, 0, mt_rand(0, 3 + $fortunel)];
		}else{
			$drops[] = [Item::WHEAT_SEEDS, 0, 1];
		}

		return $drops;
	}
}
