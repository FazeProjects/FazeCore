<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class WoodStairs extends Stair {

	protected $id = self::WOOD_STAIRS;

	/**
	 * WoodStairs constructor.
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
		return "Wood Stairs";
	}

	public function getResistance(){
		return 15;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[$this->id, 0, 1],
		];
	}

	/**
	 * @return int
	 */
	public function getBurnChance() : int{
		return 5;
	}

	/**
	 * @return int
	 */
	public function getBurnAbility() : int{
		return 20;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 2;
	}
}