<?php



namespace pocketmine\item;


class Bowl extends Item {
	/**
	 * Bowl constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BOWL, $meta, $count, "Bowl");
	}

}