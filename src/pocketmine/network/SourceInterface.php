<?php


 
/**
 * Network related classes
 */

namespace pocketmine\network;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

/**
 * Classes that implement this interface will be able to be attached to players
 */
interface SourceInterface{

	/**
	 * Performs actions needed to start the interface after it is registered.
	 */
	public function start();

	/**
	 * Sends a DataPacket to the interface, returns an unique identifier for the packet if $needACK is true
	 *
	 * @param Player     $player
	 * @param DataPacket $packet
	 * @param bool       $needACK
	 * @param bool       $immediate
	 *
	 * @return int
	 */
	public function putPacket(Player $player, DataPacket $packet, bool $needACK = false, bool $immediate = true);

	/**
	 * Terminates the connection
	 *
	 * @param Player $player
	 * @param string $reason
	 *
	 */
	public function close(Player $player, $reason = "unknown reason");

	/**
	 * @param string $name
	 */
	public function setName(string $name);

	/**
	 * Called every tick to process events on the interface.
	 */
	public function process() : void;

	public function shutdown();

	/**
	 * @deprecated
	 */
	public function emergencyShutdown();

}