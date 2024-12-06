<?php

namespace pocketmine\player;

use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\player\handler\ContainerClosePacketHandler;
use pocketmine\player\handler\InteractPacketHandler;
use pocketmine\player\handler\MoveEntityPacketHandler;

class DataPacketHandler
{

	/**
	 * @var DataPacketHandler[]
	 */
	private $handlers;

	public function __construct(Player $player)
	{
		$this->handlers = [
			ProtocolInfo::INTERACT_PACKET => new InteractPacketHandler($player),
			ProtocolInfo::MOVE_ENTITY_PACKET => new MoveEntityPacketHandler($player),
		];
	}

	public function handle(DataPacket $dataPacket): bool
	{
		if (isset($this->handlers[$dataPacket::NETWORK_ID])) {
			return $this->handlers[$dataPacket::NETWORK_ID]->handle($dataPacket);
		}

		return false;
	}
}