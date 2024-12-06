<?php



namespace pocketmine\item;


class NetherStar extends Item {
	/**
	 * NetherStar constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::NETHER_STAR, $meta, $count, "Nether Star");
	}
}