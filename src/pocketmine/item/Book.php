<?php



namespace pocketmine\item;

class Book extends Item {
	/**
	 * Book constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BOOK, $meta, $count, "Book");
	}

}

