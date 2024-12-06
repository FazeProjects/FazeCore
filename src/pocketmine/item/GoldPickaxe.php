<?php



namespace pocketmine\item;


class GoldPickaxe extends Tool {
	/**
	 * GoldPickaxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_PICKAXE, $meta, $count, "Gold Pickaxe");
	}

	/**
	 * @return int
	 */
	public function isPickaxe(){
		return Tool::TIER_GOLD;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 3;
	}
}
