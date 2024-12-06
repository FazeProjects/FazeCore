<?php



namespace pocketmine\item;

class Egg extends Item {
	/**
	 * Egg constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EGG, $meta, $count, "Egg");
	}

}

