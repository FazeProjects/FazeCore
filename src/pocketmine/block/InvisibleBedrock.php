<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class InvisibleBedrock extends Transparent {

	protected $id = self::INVISIBLE_BEDROCK;

	/**
	 * InvisibleBedrock constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Invisible Bedrock";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return -1;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 18000000;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

}
