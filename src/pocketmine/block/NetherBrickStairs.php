<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class NetherBrickStairs extends Stair {

	protected $id = self::NETHER_BRICKS_STAIRS;

	/**
	 * NetherBrickStairs constructor.
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
		return "Nether Bricks Stairs";
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
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

}