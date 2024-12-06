<?php



namespace pocketmine\item;


class StoneAxe extends Tool {
	/**
	 * StoneAxe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_AXE, $meta, $count, "Stone Axe");
	}

	/**
	 * @return int
	 */
	public function isAxe(){
		return Tool::TIER_STONE;
	}

	/**
	 * @return int
	 */
	public function getAttackDamage(){
		return 5;
	}
}