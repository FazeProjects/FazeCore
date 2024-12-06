<?php



namespace pocketmine\item;


class GoldChestplate extends Armor {
	/**
	 * GoldChestplate constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_CHESTPLATE, $meta, $count, "Gold Chestplate");
	}

	/**
	 * @return int
	 */
	public function getArmorTier(){
		return Armor::TIER_GOLD;
	}

	/**
	 * @return int
	 */
	public function getArmorType(){
		return Armor::TYPE_CHESTPLATE;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 113;
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
	public function isChestplate(){
		return true;
	}
}