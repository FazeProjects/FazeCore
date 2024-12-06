<?php



namespace pocketmine\item;


class IronBoots extends Armor {
	/**
	 * IronBoots constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_BOOTS, $meta, $count, "Iron Boots");
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
		return Armor::TYPE_BOOTS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 196;
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
	public function isBoots(){
		return true;
	}
}