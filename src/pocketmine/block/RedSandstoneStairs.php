<?php



namespace pocketmine\block;

class RedSandstoneStairs extends SandstoneStairs {

	protected $id = Block::RED_SANDSTONE_STAIRS;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Red Sandstone Stairs";
	}
}