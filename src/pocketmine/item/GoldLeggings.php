<?php



namespace pocketmine\item;


class GoldLeggings extends Armor {
	/**
	 * GoldLeggings constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_LEGGINGS, $meta, $count, "Gold Leggings");
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
		return Armor::TYPE_LEGGINGS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 106;
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
	public function isLeggings(){
		return true;
	}
}