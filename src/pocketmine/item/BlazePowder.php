<?php



namespace pocketmine\item;

class BlazePowder extends Item {
	/**
	 * BlazePowder constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BLAZE_POWDER, $meta, $count, "Blaze Powder");
	}
}
