<?php



namespace pocketmine\item;

class Clock extends Item {
	/**
	 * Clock constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CLOCK, $meta, $count, "Clock");
	}

}

