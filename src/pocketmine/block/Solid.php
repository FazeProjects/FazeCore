<?php



namespace pocketmine\block;

abstract class Solid extends Block {

	/**
	 * @return bool
	 */
	public function isSolid(){
		return true;
	}
}