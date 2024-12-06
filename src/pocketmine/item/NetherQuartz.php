<?php



namespace pocketmine\item;

class NetherQuartz extends Item {
	/**
	 * NetherQuartz constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::NETHER_QUARTZ, $meta, $count, "Nether Quartz");
	}
} 
