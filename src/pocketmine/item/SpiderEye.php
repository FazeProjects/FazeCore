<?php



namespace pocketmine\item;

use pocketmine\entity\Effect;

class SpiderEye extends Food {
	/**
	 * SpiderEye constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SPIDER_EYE, $meta, $count, "Spider Eye");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 2;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 3.2;
	}

	/**
	 * @return array
	 */
	public function getAdditionalEffects() : array{
		return [Effect::getEffect(Effect::POISON)->setDuration(80)];
	}
}
