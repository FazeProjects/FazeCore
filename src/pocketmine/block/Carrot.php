<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class Carrot extends Crops {

	protected $id = self::CARROT_BLOCK;

	/**
	 * Carrot constructor.
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
		return "Carrot Block";
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
			$drops[] = [Item::CARROT, 0, mt_rand(1, 4 + $fortunel)];
		}else{
			$drops[] = [Item::CARROT, 0, 1];
		}
		return $drops;
	}
}
