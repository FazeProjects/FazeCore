<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class EndStone extends Solid {

	protected $id = self::END_STONE;

	/**
	 * EndStone constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "End Stone";
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
}