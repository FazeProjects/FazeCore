<?php



namespace pocketmine\item;


class WoodenShovel extends Tool {
	/**
	 * WoodenShovel constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_SHOVEL, $meta, $count, "Wooden Shovel");
	}

	/**
	 * @return int
	 */
	public function isShovel(){
		return Tool::TIER_WOODEN;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 2;
	}
}
