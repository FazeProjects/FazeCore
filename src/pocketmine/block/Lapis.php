<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Lapis extends Solid {

	protected $id = self::LAPIS_BLOCK;

	/**
	 * Lapis constructor.
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
		return "Lapis Lazuli Block";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 3;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 3){
			return [
				[Item::LAPIS_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}

}