<?php



namespace pocketmine\item;

use pocketmine\entity\Effect;

interface FoodSource {
	public function getResidue();

	/**
	 * @return int
	 */
	public function getFoodRestore() : int;

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float;

	/**
	 * @return Effect[]
	 */
	public function getAdditionalEffects() : array;


}
