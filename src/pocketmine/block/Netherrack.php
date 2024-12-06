<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Netherrack extends Solid {

	protected $id = self::NETHERRACK;

	/**
	 * Netherrack constructor.
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
		return "Netherrack";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.4;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 2;
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
				[Item::NETHERRACK, 0, 1],
			];
		}else{
			return [];
		}
	}
}
