<?php



namespace pocketmine\item;


class Brick extends Item {
	/**
	 * Brick constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BRICK, $meta, $count, "Brick");
	}

}