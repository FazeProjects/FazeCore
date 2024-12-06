<?php



namespace pocketmine\item;


class Nametag extends Item {
	/**
	 * Nametag constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::NAMETAG, $meta, $count, "Nametag");
	}
}