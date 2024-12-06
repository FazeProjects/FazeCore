<?php



namespace pocketmine\block;


use pocketmine\item\Item;
use pocketmine\item\Tool;

class Bookshelf extends Solid {

	protected $id = self::BOOKSHELF;

	/**
	 * Bookshelf constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Bookshelf";
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.5;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @return int
	 */
	public function getBurnChance() : int{
		return 30;
	}

	/**
	 * @return int
	 */
	public function getBurnAbility() : int{
		return 20;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[Item::BOOK, 0, 3]
		];
	}

}