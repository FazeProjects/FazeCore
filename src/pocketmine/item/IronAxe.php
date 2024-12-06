<?php



namespace pocketmine\item;


class IronAxe extends Tool {
	/**
	 * IronAxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_AXE, $meta, $count, "Iron Axe");
	}

	/**
	 * @return int
	 */
	public function isAxe(){
		return Tool::TIER_IRON;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 6;
	}
}