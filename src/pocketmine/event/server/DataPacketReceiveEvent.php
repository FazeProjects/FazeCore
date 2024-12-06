<?php



namespace pocketmine\event\server;

use pocketmine\event\Cancellable;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

class DataPacketReceiveEvent extends ServerEvent implements Cancellable {
	public static $handlerList = null;

	private $packet;
	private $player;

	/**
	 * DataPacketReceiveEvent constructor.
	 *
	 * @param Player     $player
	 * @param DataPacket $packet
	 */
	public function __construct(Player $player, DataPacket $packet){
		$this->packet = $packet;
		$this->player = $player;
	}

	/**
	 * @return DataPacket
	 */
	public function getPacket(){
		return $this->packet;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}