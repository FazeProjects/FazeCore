<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class Dirt extends Solid {

	protected $id = self::DIRT;

	/**
	 * Dirt constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.5;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Dirt";
	}

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		if($item->isHoe()){
			$item->useOn($this, 2);
			$this->getLevel()->setBlock($this, Block::get(Item::FARMLAND, 0), true);

			return true;
		}

		return false;
	}
}