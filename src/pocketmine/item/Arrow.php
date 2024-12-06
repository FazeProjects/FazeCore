<?php

namespace pocketmine\item;

class Arrow extends Item {
	/**
	 * Arrow constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::ARROW, $meta, $count, "Arrow");
	}
}