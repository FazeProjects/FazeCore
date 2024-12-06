<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class PurpurStairs extends Stair {

	protected $id = self::PURPUR_STAIRS;

	/**
	 * PurpurStairs constructor.
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
		return 1.5;
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
		return "Purpur Stairs";
	}

	public function getResistance(){
		return 30;
	}
} 