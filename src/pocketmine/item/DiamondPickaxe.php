<?php



namespace pocketmine\item;


class DiamondPickaxe extends Tool {
	/**
	 * DiamondPickaxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_PICKAXE, $meta, $count, "Diamond Pickaxe");
	}

	/**
	 * @return int
	 */
	public function isPickaxe(){
		return Tool::TIER_DIAMOND;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 6;
	}
}
