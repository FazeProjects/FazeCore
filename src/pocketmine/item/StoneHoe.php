<?php



namespace pocketmine\item;


class StoneHoe extends Tool {
	/**
	 * StoneHoe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_HOE, $meta, $count, "Stone Hoe");
	}

	/**
	 * @return int
	 */
	public function isHoe(){
		return Tool::TIER_STONE;
	}
}