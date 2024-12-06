<?php



namespace pocketmine\item;


class StoneSword extends Tool {
	/**
	 * StoneSword constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_SWORD, $meta, $count, "Stone Sword");
	}

	/**
	 * @return int
	 */
	public function isSword(){
		return Tool::TIER_STONE;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 6;
	}
}
