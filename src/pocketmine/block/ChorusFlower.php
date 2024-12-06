<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class ChorusFlower extends Solid {

	protected $id = self::CHORUS_FLOWER;

	/**
	 * ChorusFlower constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.4;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Chorus Flower";
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		$drops = [];
		if($this->meta >= 0x07){
			$drops[] = [Item::CHORUS_FRUIT, 0, 1];
		}
		return $drops;
	}

}