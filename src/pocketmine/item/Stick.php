<?php



namespace pocketmine\item;


class Stick extends Item {
	/**
	 * Stick constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STICK, $meta, $count, "Stick");
	}

}