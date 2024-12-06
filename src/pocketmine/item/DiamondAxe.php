<?php



namespace pocketmine\item;


class DiamondAxe extends Tool {
	/**
	 * DiamondAxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_AXE, $meta, $count, "Diamond Axe");
	}

	/**
	 * @return int
	 */
	public function isAxe(){
		return Tool::TIER_DIAMOND;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 7;
	}
}