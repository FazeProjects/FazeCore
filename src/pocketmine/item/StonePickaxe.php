<?php



namespace pocketmine\item;


class StonePickaxe extends Tool {
	/**
	 * StonePickaxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_PICKAXE, $meta, $count, "Stone Pickaxe");
	}

	/**
	 * @return int
	 */
	public function isPickaxe(){
		return Tool::TIER_STONE;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 4;
	}
}
