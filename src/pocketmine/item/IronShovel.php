<?php



namespace pocketmine\item;


class IronShovel extends Tool {
	/**
	 * IronShovel constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_SHOVEL, $meta, $count, "Iron Shovel");
	}

	/**
	 * @return int
	 */
	public function isShovel(){
		return Tool::TIER_IRON;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 4;
	}
}