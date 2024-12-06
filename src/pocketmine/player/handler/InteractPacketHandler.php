<?php

namespace pocketmine\player\handler;

use pocketmine\entity\feature\Interactive;
use pocketmine\entity\Horse;
use pocketmine\event\entity\EntityRideEvent;
use pocketmine\event\TextContainer;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;

class InteractPacketHandler extends PacketHandler
{

	/**
	 * @param InteractPacket $packet
	 *
	 * @return bool
	 */
	public function handle(DataPacket $packet): bool
	{
		switch ($packet->action) {
			case InteractPacket::ACTION_RIGHT_CLICK:
				return $this->handleRightClick($packet);
			case InteractPacket::ACTION_OPEN_INVENTORY:
				return $this->handleOpenInventory($packet);
			case InteractPacket::ACTION_MOUSEOVER:
				return $this->handleMouseOver($packet);
			case InteractPacket::ACTION_LEAVE_VEHICLE:
				return $this->handleLeaveVehicle($packet);
		}

		return false;
	}

	private function handleRightClick(InteractPacket $packet): bool
	{
		$target = $this->player->getLevel()->getEntity($packet->target);

		if ($target instanceof Interactive){
			$target->onInteract($this->player, $this->player->getItemInHand());
		}elseif ($target instanceof Horse) {
			$isSneaking = $this->player->isSneaking();
			$itemInHand = $this->player->getItemInHand();
			if ($target->canFeed($itemInHand)) {
				$itemInHand->pop();
				$this->player->getInventory()->setItemInHand($itemInHand);

				$target->feed($itemInHand);
			} elseif ($isSneaking && $target->isTamed()) {
				$this->player->addWindow($target->getInventory());
			} elseif (!$isSneaking) {
				$this->player->getServer()->getPluginManager()->callEvent(
					$event = new EntityRideEvent($target, $this->player)
				);

				if (!$event->isCancelled()) {
					$target->mount($this->player);
				}
			}

			return true;
		}

		return false;
	}

	private function handleOpenInventory(InteractPacket $packet): bool
	{
		$target = $this->player->getLevel()->getEntity($packet->target);

		if ($target instanceof Horse) {
			$this->player->addWindow($target->getInventory());

			return true;
		}

		return false;
	}

	private function handleMouseOver(InteractPacket $packet): bool
	{
		$target = $this->player->getLevel()->getEntity($packet->target);

		if ($target instanceof Horse && $target->distance($this->player) <= 4) {
			$isSneaking = $this->player->isSneaking();
			if ($target->canFeed($this->player->getItemInHand())) {
				$this->player->setInteractiveTag(new TextContainer("action.interact.feed"));
			} elseif ($isSneaking && $target->isTamed()) {
				$this->player->setInteractiveTag(new TextContainer("action.interact.opencontainer"));
			} elseif (!$isSneaking) {
				$this->player->setInteractiveTag(new TextContainer("action.interact.ride.horse"));
			} else {
				$this->player->removeInteractiveTag();
			}
		} else {
			$this->player->removeInteractiveTag();
		}

		return true;
	}

	private function handleLeaveVehicle(InteractPacket $packet): bool
	{
		$target = $this->player->getLevel()->getEntity($packet->target);

		if ($target instanceof Horse) {
			$target->dismount($this->player);

			return true;
		}

		return false;
	}

}