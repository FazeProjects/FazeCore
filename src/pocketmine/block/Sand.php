<?php



namespace pocketmine\block;


use pocketmine\item\Tool;

class Sand extends Fallable {

	const NORMAL = 0;
	const RED = 1;

	protected $id = self::SAND;

	/**
	 * Sand constructor.
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
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		if($this->meta === 0x01){
			return "Red Sand";
		}

		return "Sand";
	}

}