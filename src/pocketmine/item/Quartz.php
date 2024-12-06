<?php



namespace pocketmine\item;

class Quartz extends Item {
	/**
	 * Quartz constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::QUARTZ, $meta, $count, "Quartz");
	}

}

