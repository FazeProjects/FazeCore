<?php



namespace pocketmine\block;

use pocketmine\item\Tool;

class Concrete extends Solid {

	protected $id = self::CONCRETE;

	/**
	 * Concrete constructor.
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
		return 1.8;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return mixed
	 */
	public function getName(){
		static $names = [
			0 => "White Concrete",
			1 => "Orange Concrete",
			2 => "Magenta Concrete",
			3 => "Light Blue Concrete",
			4 => "Yellow Concrete",
			5 => "Lime Concrete",
			6 => "Pink Concrete",
			7 => "Gray Concrete",
			8 => "Silver Concrete",
			9 => "Cyan Concrete",
			10 => "Purple Concrete",
			11 => "Blue Concrete",
			12 => "Brown Concrete",
			13 => "Green Concrete",
			14 => "Red Concrete",
			15 => "Black Concrete",
		];
		return $names[$this->meta & 0x0f];
	}

}