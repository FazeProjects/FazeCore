<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class BrickStairs extends Stair {

	protected $id = self::BRICK_STAIRS;

	/**
	 * BrickStairs constructor.
	 *
	 * @param int $meta
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
		return "Brick Stairs";
	}

}