<?php



namespace pocketmine\item;


class ChainHelmet extends Armor {
	/**
	 * ChainHelmet constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CHAIN_HELMET, $meta, $count, "Chainmail Helmet");
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
		return 1;
	}

	/**
	 * @return bool
	 */
	public function isHelmet(){
		return true;
	}
}