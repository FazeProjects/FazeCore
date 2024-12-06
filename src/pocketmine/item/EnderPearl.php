<?php



namespace pocketmine\item;

class EnderPearl extends Item {

	/**
	 * EnderPearl constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(Item::ENDER_PEARL, $meta, $count, "Ender Pearl");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 16;
	}

}