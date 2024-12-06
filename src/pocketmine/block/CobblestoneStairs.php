<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class CobblestoneStairs extends Stair {

	protected $id = self::COBBLESTONE_STAIRS;

	/**
	 * CobblestoneStairs constructor.
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
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Cobblestone Stairs";
	}

}