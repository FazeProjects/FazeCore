<?php



namespace pocketmine\item;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;

class GoldenApple extends Food {
	/**
	 * GoldenApple constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLDEN_APPLE, $meta, $count, "Golden Apple");
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canBeConsumedBy(Entity $entity) : bool{
		return $entity instanceof Human and $this->canBeConsumed();
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
		return 9.6;
	}

	public function requiresHunger() : bool{
		return true;
	}

	/**
	 * @return array
	 */
	public function getAdditionalEffects() : array{
		return [
			Effect::getEffect(Effect::REGENERATION)->setDuration(100)->setAmplifier(1),
			Effect::getEffect(Effect::ABSORPTION)->setDuration(2400)->setAmplifier(0)
		];
	}
}

