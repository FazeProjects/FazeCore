<?php



namespace pocketmine\item;


class IronSword extends Tool {
	/**
	 * IronSword constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_SWORD, $meta, $count, "Iron Sword");
	}

	/**
	 * @return int
	 */
	public function isSword(){
		return Tool::TIER_IRON;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 7;
	}
}