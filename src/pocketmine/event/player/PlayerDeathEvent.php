<?php


 
/*
 * Called when a player dies.
*/

namespace pocketmine\event\player;

use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\TextContainer;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerDeathEvent extends EntityDeathEvent {
	public static $handlerList = null;

	/** @var Player */
	protected $entity;

	/** @var TextContainer|string */
	private $deathMessage;
	private $keepInventory = false;
	private $keepExperience = false;

	/**
	 * @param Player               $entity
	 * @param Item[]               $drops
	 * @param string|TextContainer $deathMessage
	 */
	public function __construct(Player $entity, array $drops, $deathMessage){
		parent::__construct($entity, $drops);
		$this->deathMessage = $deathMessage;
	}

	/**
	 * @return Player
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->entity;
	}

	/**
	 * @return TextContainer|string
	 */
	public function getDeathMessage(){
		return $this->deathMessage;
	}

	/**
	 * @param string|TextContainer $deathMessage
	 */
	public function setDeathMessage($deathMessage){
		$this->deathMessage = $deathMessage;
	}

	/**
	 * @return bool
	 */
	public function getKeepInventory() : bool{
		return $this->keepInventory;
	}

	/**
	 * @param bool $keepInventory
	 */
	public function setKeepInventory(bool $keepInventory){
		$this->keepInventory = $keepInventory;
	}

	/**
	 * @return bool
	 */
	public function getKeepExperience() : bool{
		return $this->keepExperience;
	}

	/**
	 * @param bool $keepExperience
	 */
	public function setKeepExperience(bool $keepExperience){
		$this->keepExperience = $keepExperience;
	}
}