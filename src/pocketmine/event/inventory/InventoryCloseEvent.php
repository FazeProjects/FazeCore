<?php



namespace pocketmine\event\inventory;

use pocketmine\inventory\Inventory;
use pocketmine\Player;

class InventoryCloseEvent extends InventoryEvent {
	public static $handlerList = null;

	/** @var Player */
	private $who;

	/**
	 * @param Inventory $inventory
	 * @param Player    $who
	 */
	public function __construct(Inventory $inventory, Player $who){
		$this->who = $who;
		parent::__construct($inventory);
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->who;
	}

}