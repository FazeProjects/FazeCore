<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;

class NetherBrick extends Solid {

	protected $id = self::NETHER_BRICKS;

	/**
	 * NetherBrick constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Nether Bricks";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 2;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[Item::NETHER_BRICKS, 0, 1],
			];
		}else{
			return [];
		}
	}
}
