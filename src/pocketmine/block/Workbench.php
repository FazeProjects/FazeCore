<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;

class Workbench extends Solid {

	protected $id = self::WORKBENCH;

	/**
	 * Workbench constructor.
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
		return 2.5;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Crafting Table";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player){
			if($player->getServer()->limitedCreative and $player->isCreative()) return true;
			$player->craftingType = Player::CRAFTING_BIG;
		}

		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[$this->id, 0, 1],
		];
	}
}