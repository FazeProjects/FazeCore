<?php



namespace pocketmine\item;


class Feather extends Item {
	/**
	 * Feather constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FEATHER, $meta, $count, "Feather");
	}

}