<?php



namespace pocketmine\block;

use pocketmine\block\utils\ColorBlockMetaHelper;
use pocketmine\item\Tool;

class Wool extends Solid{
	const WHITE = 0;
	const ORANGE = 1;
	const MAGENTA = 2;
	const LIGHT_BLUE = 3;
	const YELLOW = 4;
	const LIME = 5;
	const PINK = 6;
	const GRAY = 7;
	const LIGHT_GRAY = 8;
	const CYAN = 9;
	const PURPLE = 10;
	const BLUE = 11;
	const BROWN = 12;
	const GREEN = 13;
	const RED = 14;
	const BLACK = 15;

	protected $id = self::WOOL;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 0.8;
	}

	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}

	public function getName() : string{
		return ColorBlockMetaHelper::getColorFromMeta($this->meta) . " Wool";
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
		return 60;
	}

}