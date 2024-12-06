<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class DaylightDetectorInverted extends DaylightDetector {
	protected $id = self::DAYLIGHT_SENSOR_INVERTED;

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		$this->getLevel()->setBlock($this, new DaylightDetector(), true, true);
		$this->getTile()->onUpdate();
		return true;
	}
}