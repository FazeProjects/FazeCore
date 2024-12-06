<?php



namespace pocketmine\item;


class LeatherTunic extends Armor {
	/**
	 * LeatherTunic constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::LEATHER_TUNIC, $meta, $count, "Leather Tunic");
	}

	/**
	 * @return int
	 */
	public function getArmorTier(){
		return Armor::TIER_LEATHER;
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
		return 81;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 3;
	}

	/**
	 * @return bool
	 */
	public function isChestplate(){
		return true;
	}
}