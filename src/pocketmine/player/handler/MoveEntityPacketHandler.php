<?php

namespace pocketmine\player\handler;

use pocketmine\entity\Horse;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;

class MoveEntityPacketHandler extends PacketHandler
{

	/**
	 * @param MoveEntityPacket $packet
	 *
	 * @return bool
	 */
	public function handle(DataPacket $packet): bool
	{
		$target = $this->player->getLevel()->getEntity($packet->eid);

		if ($target instanceof Horse && $target->getLinkedEntity() !== null) {
			if ($target->setPositionTo($packet->x, $packet->y, $packet->z)) {
				$target->setRotation($packet->yaw, $packet->pitch);

				return true;
			}
		}

		return false;
	}
}