<?php



namespace pocketmine\item;

class Compass extends Item {
	/**
	 * Compass constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COMPASS, $meta, $count, "Compass");
	}

}

