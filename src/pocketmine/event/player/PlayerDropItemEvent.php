<?php



namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when the player drops an item.
 */
class PlayerDropItemEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Item */
	private $drop;

	/**
	 * @param Player $player
	 * @param Item   $drop
	 */
	public function __construct(Player $player, Item $drop){
		$this->player = $player;
		$this->drop = $drop;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->drop;
	}

}