<?php



namespace pocketmine\item;

class GhastTear extends Item {
	/**
	 * GhastTear constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GHAST_TEAR, $meta, $count, "Ghast Tear");
	}
}