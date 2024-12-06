<?php



namespace pocketmine\item;


class GoldBoots extends Armor {
	/**
	 * GoldBoots constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_BOOTS, $meta, $count, "Gold Boots");
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
		return Armor::TYPE_BOOTS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 92;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 1;
	}

	/**
	 * @return bool
	 */
	public function isBoots(){
		return true;
	}
}