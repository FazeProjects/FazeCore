<?php



namespace pocketmine\item;


class IronChestplate extends Armor {
	/**
	 * IronChestplate constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_CHESTPLATE, $meta, $count, "Iron Chestplate");
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
		return Armor::TYPE_CHESTPLATE;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 241;
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
	public function isChestplate(){
		return true;
	}
}