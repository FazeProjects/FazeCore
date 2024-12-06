<?php



namespace pocketmine\item;

class Camera extends Item {
	/**
	 * Camera constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CAMERA, $meta, $count, "Camera");
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}
}
