<?php



namespace pocketmine\block;

class ActivatorRail extends PoweredRail {

	protected $id = self::ACTIVATOR_RAIL;

	/**
	 * ActivatorRail constructor.
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
		return "Activator Rail";
	}
}
