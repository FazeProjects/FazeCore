<?php



namespace pocketmine\item;


class WoodenSword extends Tool {
	/**
	 * WoodenSword constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_SWORD, $meta, $count, "Wooden Sword");
	}

	/**
	 * @return int
	 */
	public function isSword(){
		return Tool::TIER_WOODEN;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 5;
	}
}
