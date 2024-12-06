<?php



namespace pocketmine\item;

class Flint extends Item {
	/**
	 * Flint constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FLINT, $meta, $count, "Flint");
	}

}

