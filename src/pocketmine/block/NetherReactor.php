<?php



namespace pocketmine\block;


class NetherReactor extends Solid {

	protected $id = self::NETHER_REACTOR;

	/**
	 * NetherReactor constructor.
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
		return "Nether Reactor";
	}
}