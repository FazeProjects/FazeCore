<?php



namespace pocketmine\item;


class DiamondLeggings extends Armor {
	/**
	 * DiamondLeggings constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_LEGGINGS, $meta, $count, "Diamond Leggings");
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
		return Armor::TYPE_LEGGINGS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 496;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 6;
	}

	/**
	 * @return bool
	 */
	public function isLeggings(){
		return true;
	}
}