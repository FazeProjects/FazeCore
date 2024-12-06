<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class StoneBrickStairs extends Stair {

	protected $id = self::STONE_BRICK_STAIRS;

	/**
	 * StoneBrickStairs constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.5;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Stone Brick Stairs";
	}

}