<?php



namespace pocketmine\block;

use pocketmine\item\Tool;

class ConcretePowder extends Fallable {

	protected $id = self::CONCRETE_POWDER;

	/**
	 * ConcretePowder constructor.
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
		return 0.5;
	}

	/**
	 * @return float
	 */
	public function getResistance(){
		return 2.5;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return mixed
	 */
	public function getName(){
		static $names = [
			0 => "White Concrete Powder",
			1 => "Orange Concrete Powder",
			2 => "Magenta Concrete Powder",
			3 => "Light Blue Concrete Powder",
			4 => "Yellow Concrete Powder",
			5 => "Lime Concrete Powder",
			6 => "Pink Concrete Powder",
			7 => "Gray Concrete Powder",
			8 => "Silver Concrete Powder",
			9 => "Cyan Concrete Powder",
			10 => "Purple Concrete Powder",
			11 => "Blue Concrete Powder",
			12 => "Brown Concrete Powder",
			13 => "Green Concrete Powder",
			14 => "Red Concrete Powder",
			15 => "Black Concrete Powder",
		];
		return $names[$this->meta & 0x0f];
	}

}
