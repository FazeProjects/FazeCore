<?php



namespace pocketmine\item;

class Emerald extends Item {
	/**
	 * Emerald constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EMERALD, $meta, $count, "Emerald");
	}

}

