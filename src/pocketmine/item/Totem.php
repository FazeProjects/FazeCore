<?php



namespace pocketmine\item;

class Totem extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::TOTEM, $meta, $count, "Totem of Undying");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}
}