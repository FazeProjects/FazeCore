<?php



namespace pocketmine\item;

class GlowstoneDust extends Item {
	/**
	 * GlowstoneDust constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GLOWSTONE_DUST, $meta, $count, "Glowstone Dust");
	}

}

