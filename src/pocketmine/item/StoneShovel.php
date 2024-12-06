<?php



namespace pocketmine\item;


class StoneShovel extends Tool {
	/**
	 * StoneShovel constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_SHOVEL, $meta, $count, "Stone Shovel");
	}

	/**
	 * @return int
	 */
	public function isShovel(){
		return Tool::TIER_STONE;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 3;
	}
}
