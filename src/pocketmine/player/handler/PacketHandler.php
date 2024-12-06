<?php

namespace pocketmine\player\handler;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\Player;

abstract class PacketHandler
{

	protected $player;

	public function __construct(Player $player)
	{
		$this->player = $player;
	}

	/**
	 * @param DataPacket $packet
	 * @return bool true, if you need to pass the processing further to Player::handleDataPacket
	 */
	public abstract function handle(DataPacket $packet): bool;

}