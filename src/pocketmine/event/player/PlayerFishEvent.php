<?php



namespace pocketmine\event\player;

use pocketmine\entity\FishingHook;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when the player uses a fishing rod.
 */
class PlayerFishEvent extends PlayerEvent implements Cancellable {

	public static $handlerList = null;

	/** @var Item */
	private $item;

	/** @var FishingHook */
	private $hook;

	/**
	 * @param Player $player
	 * @param Item   $item
	 * @param        $fishingHook
	 */
	public function __construct(Player $player, Item $item, $fishingHook = null){
		$this->player = $player;
		$this->item = $item;
		$this->hook = $fishingHook;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return clone $this->item;
	}

	/**
	 * @return null|FishingHook
	 */
	public function getHook(){
		return $this->hook;
	}
}
