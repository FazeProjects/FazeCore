<?php



namespace pocketmine\item;

class NetherBrick extends Item {
	/**
	 * NetherBrick constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::NETHER_BRICK, $meta, $count, "Nether Brick");
	}

}

