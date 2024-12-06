<?php



namespace pocketmine\block;


abstract class Transparent extends Block {

	/**
	 * @return bool
	 */
	public function isTransparent(){
		return true;
	}

	public function getLightFilter() : int{
		return 0;
	}
}