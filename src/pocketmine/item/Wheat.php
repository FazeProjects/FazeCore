<?php



namespace pocketmine\item;

class Wheat extends Item {
	/**
	 * Wheat constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WHEAT, $meta, $count, "Wheat");
	}

}

