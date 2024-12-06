<?php



namespace pocketmine\item;


class WoodenAxe extends Tool {
	/**
	 * WoodenAxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_AXE, $meta, $count, "Wooden Axe");
	}

	/**
	 * @return int
	 */
	public function isAxe(){
		return Tool::TIER_WOODEN;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 4;
	}
}
