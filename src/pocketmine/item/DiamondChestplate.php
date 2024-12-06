<?php



namespace pocketmine\item;


class DiamondChestplate extends Armor {
	/**
	 * DiamondChestplate constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_CHESTPLATE, $meta, $count, "Diamond Chestplate");
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
		return Armor::TYPE_CHESTPLATE;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 529;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 8;
	}

	/**
	 * @return bool
	 */
	public function isChestplate(){
		return true;
	}
}