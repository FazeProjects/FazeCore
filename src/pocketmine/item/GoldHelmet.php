<?php



namespace pocketmine\item;


class GoldHelmet extends Armor {
	/**
	 * GoldHelmet constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_HELMET, $meta, $count, "Gold Helmet");
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
		return Armor::TYPE_HELMET;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 78;
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
	public function isHelmet(){
		return true;
	}
}