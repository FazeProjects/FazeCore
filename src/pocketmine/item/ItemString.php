<?php



namespace pocketmine\item;

class ItemString extends Item {
	/**
	 * ItemString constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STRING, $meta, $count, "String");
	}

}