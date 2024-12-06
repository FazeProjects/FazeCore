<?php



namespace pocketmine\item;


class LeatherPants extends Armor {
	/**
	 * LeatherPants constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::LEATHER_PANTS, $meta, $count, "Leather Pants");
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
		return Armor::TYPE_LEGGINGS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 76;
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
	public function isLeggings(){
		return true;
	}
}