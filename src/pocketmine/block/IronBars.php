<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class IronBars extends Thin {

	protected $id = self::IRON_BARS;

	/**
	 * IronBars constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Iron Bars";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 5;
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
		if($item->isPickaxe() >= 1){
			return [
				[Item::IRON_BARS, 0, 1],
			];
		}else{
			return [];
		}
	}

}

