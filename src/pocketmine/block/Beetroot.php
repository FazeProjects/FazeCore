<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class Beetroot extends Crops {

	protected $id = self::BEETROOT_BLOCK;

	/**
	 * Beetroot constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Beetroot Block";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		$drops = [];
		if($this->meta >= 0x07){
			$drops[] = [Item::BEETROOT, 0, 1];
			$drops[] = [Item::BEETROOT_SEEDS, 0, mt_rand(0, 3)];
		}else{
			$drops[] = [Item::BEETROOT_SEEDS, 0, 1];
		}

		return $drops;
	}
}