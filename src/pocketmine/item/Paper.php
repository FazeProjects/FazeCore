<?php



namespace pocketmine\item;

class Paper extends Item {
	/**
	 * Paper constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PAPER, $meta, $count, "Paper");
	}

}

