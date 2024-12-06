<?php



namespace pocketmine\item;


class DiamondHoe extends Tool {
	/**
	 * DiamondHoe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_HOE, $meta, $count, "Diamond Hoe");
	}

	/**
	 * @return int
	 */
	public function isHoe(){
		return Tool::TIER_DIAMOND;
	}
}