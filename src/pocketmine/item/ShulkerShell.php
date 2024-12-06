<?php



namespace pocketmine\item;


class ShulkerShell extends Item {
	/**
	 * ShulkerShell constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SHULKER_SHELL, $meta, $count, "Shulker Shell");
	}

}