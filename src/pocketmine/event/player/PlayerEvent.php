<?php



/**
 * Player-only events
 */

namespace pocketmine\event\player;

use pocketmine\event\Event;

abstract class PlayerEvent extends Event {
	/** @var \pocketmine\Player */
	protected $player;

	/**
	 * @return \pocketmine\Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}