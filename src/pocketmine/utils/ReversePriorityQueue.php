<?php



namespace pocketmine\utils;

class ReversePriorityQueue extends \SplPriorityQueue {

	/**
	 * @param mixed $priority1
	 * @param mixed $priority2
	 *
	 * @return int
	 */
	public function compare($priority1, $priority2){
		return (int) -($priority1 - $priority2);
	}
}