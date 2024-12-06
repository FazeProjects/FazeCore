<?php



namespace pocketmine\item;

class RabbitStew extends Food {
	/**
	 * RabbitStew constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RABBIT_STEW, $meta, $count, "Rabbit Stew");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 10;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 12;
	}

	/**
	 * @return Item
	 */
	public function getResidue(){
		return Item::get(Item::BOWL);
	}
}
