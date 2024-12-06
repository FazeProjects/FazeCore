<?php



namespace pocketmine\item;


class PrismarineCrystals extends Item {
	/**
	 * PrismarineCrystals constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PRISMARINE_CRYSTALS, $meta, $count, "Prismarine Crystals");
	}
}