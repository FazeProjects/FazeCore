<?php



namespace pocketmine\item;

class FermentedSpiderEye extends Item {
	/**
	 * FermentedSpiderEye constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FERMENTED_SPIDER_EYE, $meta, $count, "Fermented Spider Eye");
	}
}
