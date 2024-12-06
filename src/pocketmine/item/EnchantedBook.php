<?php



namespace pocketmine\item;

class EnchantedBook extends Item {
	/**
	 * EnchantedBook constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::ENCHANTED_BOOK, $meta, $count, "Enchanted Book");
	}
}