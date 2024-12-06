<?php



namespace pocketmine\block;

class RedstoneLamp extends Solid {
	protected $id = self::REDSTONE_LAMP;

	/**
	 * RedstoneLamp constructor.
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
		return "Redstone Lamp";
	}

	/**
	 * @return bool
	 */
	public function turnOn(){
		$this->getLevel()->setBlock($this, new LitRedstoneLamp(), true, true);
		return true;
	}

	public function getHardness(){
		return 0.3;
	}
}
