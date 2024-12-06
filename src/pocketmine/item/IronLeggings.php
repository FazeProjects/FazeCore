<?php



namespace pocketmine\item;


class IronLeggings extends Armor {
	/**
	 * IronLeggings constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_LEGGINGS, $meta, $count, "Iron Leggings");
	}

	/**
	 * @return int
	 */
	public function getArmorTier(){
		return Armor::TIER_IRON;
	}

	/**
	 * @return int
	 */
	public function getArmorType(){
		return Armor::TYPE_LEGGINGS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 226;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 5;
	}

	/**
	 * @return bool
	 */
	public function isLeggings(){
		return true;
	}
}