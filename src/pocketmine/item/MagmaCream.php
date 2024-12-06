<?php



namespace pocketmine\item;

class MagmaCream extends Item {
	/**
	 * MagmaCream constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::MAGMA_CREAM, $meta, $count, "Magma Cream");
	}
}
