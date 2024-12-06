<?php



namespace pocketmine\block;

use pocketmine\Player;
use pocketmine\item\Item;

use pocketmine\item\Tool;

class PurpleGlazedTerracotta extends Solid {

	protected $id = self::PURPLE_GLAZED_TERRACOTTA;

	/**
	 * PurpleGlazedTerracotta constructor.
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
		return 1.4;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "Purple Glazed Terracotta";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		];
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}
}