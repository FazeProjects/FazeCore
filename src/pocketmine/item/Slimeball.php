<?php



namespace pocketmine\item;

class Slimeball extends Item {
	/**
	 * Slimeball constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SLIMEBALL, $meta, $count, "Slimeball");
	}

}

