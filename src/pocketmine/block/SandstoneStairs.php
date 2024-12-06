<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class SandstoneStairs extends Stair {

	protected $id = self::SANDSTONE_STAIRS;

	/**
	 * SandstoneStairs constructor.
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
		return "Sandstone Stairs";
	}

}