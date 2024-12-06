<?php



namespace pocketmine\block;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class GlassPane extends Thin {

	protected $id = self::GLASS_PANE;

	/**
	 * GlassPane constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Glass Pane";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.3;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::GLASS_PANE, 0, 1],
			];
		}else{
			return [];
		}
	}
}