<?php



namespace pocketmine\item;


class DiamondShovel extends Tool {
	/**
	 * DiamondShovel constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_SHOVEL, $meta, $count, "Diamond Shovel");
	}

	/**
	 * @return int
	 */
	public function isShovel(){
		return Tool::TIER_DIAMOND;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 5;
	}
}
