<?php



namespace pocketmine\item;


class Diamond extends Item {
	/**
	 * Diamond constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND, $meta, $count, "Diamond");
	}

}