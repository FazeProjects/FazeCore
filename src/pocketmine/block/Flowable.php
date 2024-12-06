<?php



namespace pocketmine\block;

abstract class Flowable extends Transparent {

	/**
	 * @return bool
	 */
	public function canBeFlowedInto(){
		return true;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	protected function recalculateBoundingBox(){
		return null;
	}
}