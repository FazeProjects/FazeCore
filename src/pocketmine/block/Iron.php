<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Iron extends Solid {

	protected $id = self::IRON_BLOCK;

	/**
	 * Iron constructor.
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
		return "Iron Block";
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
		return 5;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 3){
			return [
				[Item::IRON_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}