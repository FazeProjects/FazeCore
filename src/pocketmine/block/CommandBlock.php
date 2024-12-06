<?php



namespace pocketmine\block;

class CommandBlock extends Solid {
	protected $id = self::COMMAND_BLOCK;

	/**
	 * CommandBlock constructor.
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
		return "Command Block";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return -1;
	}

}
