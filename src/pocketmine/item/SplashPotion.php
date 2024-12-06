<?php



namespace pocketmine\item;

class SplashPotion extends Item {

	/**
	 * SplashPotion constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SPLASH_POTION, $meta, $count, $this->getNameByMeta($meta));
	}

	/**
	 * @return int
	 */
	public function getMaxStackSize() : int{
		return 1;
	}

	/**
	 * @param int $meta
	 *
	 * @return string
	 */
	public function getNameByMeta(int $meta){
		return "Splash " . Potion::getNameByMeta($meta);
	}


}