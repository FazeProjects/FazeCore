<?php



namespace pocketmine\item;


class WoodenPickaxe extends Tool {
	/**
	 * WoodenPickaxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_PICKAXE, $meta, $count, "Wooden Pickaxe");
	}

	/**
	 * @return int
	 */
	public function isPickaxe(){
		return Tool::TIER_WOODEN;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 3;
	}
}
