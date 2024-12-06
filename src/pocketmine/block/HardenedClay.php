<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class HardenedClay extends Solid {

	protected $id = self::HARDENED_CLAY;

	/**
	 * HardenedClay constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Hardened Clay";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.25;
	}
}