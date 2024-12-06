<?php



namespace pocketmine\item;


class ChainBoots extends Armor {
	/**
	 * ChainBoots constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CHAIN_BOOTS, $meta, $count, "Chainmail Boots");
	}

	/**
	 * @return int
	 */
	public function getArmorTier(){
		return Armor::TIER_CHAIN;
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
		return 1;
	}

	/**
	 * @return bool
	 */
	public function isBoots(){
		return true;
	}
}