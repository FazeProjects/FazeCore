<?php



namespace pocketmine\block;

use pocketmine\item\Tool;

class Tripwire extends Transparent {

	protected $id = self::TRIPWIRE;

	/**
	 * Tripwire constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Tripwire";
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

}
