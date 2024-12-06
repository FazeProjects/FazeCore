<?php



namespace pocketmine\item;


class DiamondSword extends Tool {
	/**
	 * DiamondSword constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_SWORD, $meta, $count, "Diamond Sword");
	}

	/**
	 * @return int
	 */
	public function isSword(){
		return Tool::TIER_DIAMOND;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 8;
	}
}
