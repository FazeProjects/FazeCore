<?php



namespace pocketmine\item;


class ChainLeggings extends Armor {
	/**
	 * ChainLeggings constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CHAIN_LEGGINGS, $meta, $count, "Chain Leggings");
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
		return Armor::TYPE_LEGGINGS;
	}

	/**
	 * @return int
	 */
	public function getMaxDurability(){
		return 226;
	}

	/**
	 * @return int
	 */
	public function getArmorValue(){
		return 4;
	}

	/**
	 * @return bool
	 */
	public function isLeggings(){
		return true;
	}
}