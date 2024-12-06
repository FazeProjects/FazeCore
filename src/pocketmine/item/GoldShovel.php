<?php



namespace pocketmine\item;


class GoldShovel extends Tool {
	/**
	 * GoldShovel constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_SHOVEL, $meta, $count, "Gold Shovel");
	}

	/**
	 * @return int
	 */
	public function isShovel(){
		return Tool::TIER_GOLD;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 2;
	}
}
