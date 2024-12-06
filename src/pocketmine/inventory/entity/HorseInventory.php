<?php

namespace pocketmine\inventory\entity;

use pocketmine\block\BlockIds;
use pocketmine\entity\Horse;
use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\item\Item;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\InventoryNetworkIds;
use pocketmine\network\mcpe\protocol\UpdateEquipPacket;
use pocketmine\Player;

class HorseInventory extends BaseInventory
{

	private const EQUIP_DATA = "0a000905736c6f74730a04090d61636365707465644974656d730a020a08736c6f744974656d0105436f756e7401020644616d616765000002026964490100000a046974656d0105436f756e7401020644616d616765000002026964490100030a736c6f744e756d6265720000090d61636365707465644974656d730a080a08736c6f744974656d0105436f756e7401020644616d616765000002026964a00100000a08736c6f744974656d0105436f756e7401020644616d616765000002026964a10100000a08736c6f744974656d0105436f756e7401020644616d616765000002026964a20100000a08736c6f744974656d0105436f756e7401020644616d616765000002026964a30100000a046974656d0105436f756e7401020644616d616765000002026964a10100030a736c6f744e756d626572020000";

	/** @var Horse */
	protected $holder;

	public function __construct(Horse $holder, array $items = [])
	{
		parent::__construct($holder, InventoryType::get(InventoryType::CHEST), $items);
	}

	public function getName(): string
	{
		return "HorseInventory";
	}

	public function getSize(): int
	{
		return 2;
	}

	public function onOpen(Player $who)
	{
		parent::onOpen($who);

		$who->dataPacket(
			UpdateEquipPacket::create(
				$this->holder->getId(),
				$who->getWindowId($this),
				InventoryNetworkIds::HORSE,
				hex2bin(self::EQUIP_DATA),
			)
		);

		$this->sendContents($who);
		if ($this->getItem(0)->getId() === ItemIds::SADDLE) {
			$this->playSaddleSound();
		}
	}

	public function onSlotChange($index, $before, $send)
	{
		parent::onSlotChange($index, $before, $send);

		if ($index == 0) {
			if ($this->getItem($index)->getId() == ItemIds::SADDLE) {
				$this->holder->setSaddledDataFlags(true);

				$this->playSaddleSound();
			} else {
				$this->holder->setSaddledDataFlags(false);
			}
		} elseif ($index == 1) {
			$this->holder->syncChestPlate();

			if ($this->getItem($index)->getId() !== 0) {
				$this->playArmorSound();
			}
		}
	}

	private function playSaddleSound(): void
	{
		$this->holder->getLevel()->broadcastLevelSoundEvent(
			$this->holder,
			LevelSoundEventPacket::SOUND_SADDLE,
			2118423
		);
	}

	private function playArmorSound(): void
	{
		$this->holder->getLevel()->broadcastLevelSoundEvent(
			$this->holder,
			LevelSoundEventPacket::SOUND_ARMOR,
			2118423
		);
	}

	public function setChestPlate(Item $item)
	{
		if ($item->getId() === ItemIds::LEATHER_HORSE_ARMOR
			|| $item->getId() === ItemIds::IRON_HORSE_ARMOR
			|| $item->getId() === ItemIds::GOLD_HORSE_ARMOR
			|| $item->getId() === ItemIds::DIAMOND_HORSE_ARMOR
			|| $item->getId() === BlockIds::AIR) {
			$this->setItem(1, $item);
		}
	}

	public function getChestPlate(): Item
	{
		return $this->getItem(1);
	}

	public function setSaddled(bool $isSaddled): void
	{
		$this->setItem(
			0,
			$isSaddled ? ItemItem::get(ItemIds::SADDLE) : ItemItem::get(BlockIds::AIR),
		);
	}
}