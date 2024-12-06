<?php



namespace pocketmine\item;


class Shears extends Tool {
	/**
	 * Shears constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SHEARS, $meta, $count, "Shears");
	}
}