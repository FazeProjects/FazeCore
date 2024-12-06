<?php



namespace pocketmine\item;


class IronPickaxe extends Tool {
	/**
	 * IronPickaxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_PICKAXE, $meta, $count, "Iron Pickaxe");
	}

	/**
	 * @return int
	 */
	public function isPickaxe(){
		return Tool::TIER_IRON;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 5;
	}
}