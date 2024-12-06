<?php



namespace pocketmine\item;


class GoldAxe extends Tool {
	/**
	 * GoldAxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_AXE, $meta, $count, "Gold Axe");
	}

	/**
	 * @return int
	 */
	public function isAxe(){
		return Tool::TIER_GOLD;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 4;
	}
}