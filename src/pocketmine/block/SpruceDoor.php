<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class SpruceDoor extends Door {

	protected $id = self::SPRUCE_DOOR_BLOCK;

	/**
	 * SpruceDoor constructor.
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
		return "Spruce Door Block";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 3;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[Item::SPRUCE_DOOR, 0, 1],
		];
	}
}