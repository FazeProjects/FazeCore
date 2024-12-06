<?php



namespace pocketmine\item;


class IronHelmet extends Armor {
	/**
	 * IronHelmet constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_HELMET, $meta, $count, "Iron Helmet");
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
		return Armor::TYPE_HELMET;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 166;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 2;
	}

	/**
	 * @return bool
	 */
	public function isHelmet(){
		return true;
	}
}