<?php



namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\sound\ButtonClickSound;
use pocketmine\Player;

class StoneButton extends WoodenButton {
	protected $id = self::STONE_BUTTON;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Stone Button";
	}

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		if(!$this->isActivated()){
			$this->meta ^= 0x08;
			$this->getLevel()->setBlock($this, $this, true, false);
			$this->getLevel()->addSound(new ButtonClickSound($this));
			$this->activate();
			$this->getLevel()->scheduleUpdate($this, 40);
		}
		return true;
	}
}
