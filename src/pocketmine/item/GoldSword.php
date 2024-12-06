<?php



namespace pocketmine\item;


class GoldSword extends Tool {
	/**
	 * GoldSword constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_SWORD, $meta, $count, "Gold Sword");
	}

	/**
	 * @return int
	 */
	public function isSword(){
		return Tool::TIER_GOLD;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 5;
	}
}
