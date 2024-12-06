<?php



namespace pocketmine\item;

use pocketmine\entity\Effect;

class RottenFlesh extends Food {
	/**
	 * RottenFlesh constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::ROTTEN_FLESH, $meta, $count, "Rotten Flesh");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 4;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 0.8;
	}

	/**
	 * @return array
	 */
	public function getAdditionalEffects() : array{
		$chance = mt_rand(0, 100);
		if($chance >= 20){
			return [Effect::getEffect(Effect::HUNGER)->setDuration(30 * 20)];
		}else{
			return [];
		}
	}
}