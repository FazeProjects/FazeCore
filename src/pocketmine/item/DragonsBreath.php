<?php



namespace pocketmine\item;


class DragonsBreath extends Item {
	/**
	 * DragonsBreath constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DRAGONS_BREATH, $meta, $count, "Dragon's Breath");
	}

}