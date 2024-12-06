<?php



namespace pocketmine\item;


class WoodenHoe extends Tool {
	/**
	 * WoodenHoe constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_HOE, $meta, $count, "Wooden Hoe");
	}

	/**
	 * @return int
	 */
	public function isHoe(){
		return Tool::TIER_WOODEN;
	}
}