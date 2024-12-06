<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Bricks extends Solid {

	protected $id = self::BRICKS_BLOCK;

	/**
	 * Bricks constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 2;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 30;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Bricks";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[Item::BRICKS_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}