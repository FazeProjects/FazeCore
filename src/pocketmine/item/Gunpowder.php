<?php



namespace pocketmine\item;

class Gunpowder extends Item {
	/**
	 * Gunpowder constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GUNPOWDER, $meta, $count, "Gunpowder");
	}

}

