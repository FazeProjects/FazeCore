<?php



namespace pocketmine\item;


class IronIngot extends Item {
	/**
	 * IronIngot constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_INGOT, $meta, $count, "Iron Ingot");
	}

}