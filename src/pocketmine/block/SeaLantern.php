<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;

class SeaLantern extends Transparent{

	protected $id = self::SEA_LANTERN;

	/**
	 * SeaLantern constructor.
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
		return "Sea Lantern";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.3;
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 15;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->hasEnchantment(Enchantment::TYPE_MINING_SILK_TOUCH)){
			return [
				[$this->id, 0, 1],
			];
		}
		return [
			[Item::PRISMARINE_CRYSTALS, 0, 3],
		];
	}

}