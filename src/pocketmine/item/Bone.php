<?php



namespace pocketmine\item;

class Bone extends Item {
	/**
	 * Bone constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BONE, $meta, $count, "Bone");
	}

}

