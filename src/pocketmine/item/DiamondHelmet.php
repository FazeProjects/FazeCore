<?php



namespace pocketmine\item;


class DiamondHelmet extends Armor {
	/**
	 * DiamondHelmet constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_HELMET, $meta, $count, "Diamond Helmet");
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
		return Armor::TYPE_HELMET;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 364;
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
	public function isHelmet(){
		return true;
	}
}