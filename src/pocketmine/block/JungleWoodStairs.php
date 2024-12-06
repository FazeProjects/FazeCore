<?php



namespace pocketmine\block;

class JungleWoodStairs extends WoodStairs {

	protected $id = self::JUNGLE_WOOD_STAIRS;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Jungle Wood Stairs";
	}
}