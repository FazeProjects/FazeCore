<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Clay extends Solid {

	protected $id = self::CLAY_BLOCK;

	/**
	 * Clay constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.6;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Clay Block";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[Item::CLAY, 0, 4],
		];
	}
}