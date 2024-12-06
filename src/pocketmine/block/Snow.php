<?php



namespace pocketmine\block;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Snow extends Solid {

	protected $id = self::SNOW_BLOCK;

	/**
	 * Snow constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.2;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Snow Block";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isShovel() !== false){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::SNOW_BLOCK, 0, 1],
				];
			}else{
				return [
					[Item::SNOWBALL, 0, 4],
				];
			}
		}else{
			return [];
		}
	}
}
