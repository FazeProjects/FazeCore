<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class QuartzStairs extends Stair {

	protected $id = self::QUARTZ_STAIRS;

	/**
	 * QuartzStairs constructor.
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
		return 0.8;
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
		return "Quartz Stairs";
	}

}