<?php



namespace pocketmine\block;


class SlimeBlock extends Solid {

	protected $id = self::SLIME_BLOCK;

	/**
	 * SlimeBlock constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 15){
		$this->meta = $meta;
	}

	/**
	 * @return bool
	 */
	public function hasEntityCollision(){
		return true;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Slime Block";
	}
}
