<?php



namespace pocketmine\item;

abstract class Door extends Item {
	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 64;
	}
}