<?php



namespace pocketmine\item;


class GoldHoe extends Tool {
	/**
	 * GoldHoe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_HOE, $meta, $count, "Gold Hoe");
	}

	/**
	 * @return int
	 */
	public function isHoe(){
		return Tool::TIER_GOLD;
	}
}