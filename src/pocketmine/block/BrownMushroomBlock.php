<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;


class BrownMushroomBlock extends Solid {

	const BROWN = 14;

	protected $id = self::BROWN_MUSHROOM_BLOCK;

	/**
	 * BrownMushroomBlock constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 14){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Brown Mushroom Block";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.2;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 1;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::BROWN_MUSHROOM_BLOCK, self::BROWN, 1],
			];
		}else{
			return [
				[Item::BROWN_MUSHROOM, 0, mt_rand(0, 2)],
			];
		}
	}
}
