<?php



namespace pocketmine\item;


class DiamondBoots extends Armor {
	/**
	 * DiamondBoots constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_BOOTS, $meta, $count, "Diamond Boots");
	}

	/**
	 * @return int
	 */
	public function getArmorTier(){
		return Armor::TIER_DIAMOND;
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
		return 430;
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
	public function isBoots(){
		return true;
	}
}