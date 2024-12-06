<?php



namespace pocketmine\item;

class Leather extends Item {
	/**
	 * Leather constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::LEATHER, $meta, $count, "Leather");
	}

}

