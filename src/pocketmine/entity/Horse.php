<?php

/**
 * created at 21.07.2022 02:20
 *
 * @author xISRAPILx
 */

namespace pocketmine\entity;

use pocketmine\block\BlockIds;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\inventory\entity\HorseInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\Player;

class Horse extends Living implements Rideable, Feedable, InventoryHolder
{

	public const NETWORK_ID = 23;

	public const FOOD = [
		ItemIds::SUGAR => 1,
		ItemIds::WHEAT => 2,
		ItemIds::APPLE => 3,
		ItemIds::GOLDEN_CARROT => 4,
		ItemIds::GOLDEN_APPLE => 10,
		BlockIds::HAY_BALE => 20,
	];

	/** @var float */
	public $width = 1.4;

	/** @var float */
	public $height = 1.6;

	/** @var float */
	public $eyeHeight = 1.55;

	private $jumpStrength = 0.7;
	private $movementSpeed = 0.225;

	private $inventory;

	public function __construct(Level $level, CompoundTag $nbt)
	{
		$this->inventory = new HorseInventory($this);

		parent::__construct($level, $nbt);
	}

	/**
	 * Лошадь может кушать:
	 * - Бесконечно, если не приручена
	 * - До полного ХП, если приручена
	 *
	 * @param ItemItem $item
	 *
	 * @return bool
	 */
	public function canFeed(ItemItem $item): bool
	{
		return isset(self::FOOD[$item->getId()]) && (!$this->isTamed() || $this->getHealth() < $this->getMaxHealth());
	}

	public function feed(ItemItem $item): void
	{
		if (!isset(self::FOOD[$item->getId()])) return;

		$amount = self::FOOD[$item->getId()];
		$event = new EntityRegainHealthEvent($this, $amount, EntityRegainHealthEvent::CAUSE_EATING);
		$this->heal($amount, $event);

		if (!$event->isCancelled()) {
			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			$pk->event = EntityEventPacket::EATING_ITEM;
			$pk->data = $item->getId();

			$this->level->broadcastPacketToViewers($this, $pk);
		}
	}

	protected function initEntity(): void
	{
		parent::initEntity();

		$this->setTamed(true);

		$isSaddled = $this->namedtag->getBoolean("IsSaddled");
		$this->inventory->setSaddled($isSaddled);
		$this->setSaddledDataFlags($isSaddled);

		$this->inventory->setChestPlate(ItemItem::get($this->namedtag->getInt("ArmorId") ?? BlockIds::AIR));

		$this->setVariant($this->namedtag->getInt("Variant") ?? HorseVariant::getRandomColor());

		$this->setMaxHealth($this->namedtag->getFloat("HMaxHealth") ?? mt_rand(15, 30));
		$this->setHealth($this->namedtag->getFloat("HHealth") ?? $this->getMaxHealth());

		$this->setJumpStrength($this->namedtag->getFloat("JumpStrength") ?? (mt_rand(4, 10) / 10));
		$this->setMovementSpeed($this->namedtag->getFloat("MovementSpeed") ?? (mt_rand(1125, 3365) / 10000));
	}

	public function saveNBT(): void
	{
		parent::saveNBT();

		$this->namedtag->putBoolean("IsSaddled", $this->isSaddled());
		$this->namedtag->putInt("ArmorId", $this->inventory->getChestPlate()->getId());
		$this->namedtag->putInt("Variant", $this->getVariant());
		$this->namedtag->putFloat("HMaxHealth", $this->getMaxHealth());
		$this->namedtag->putFloat("HHealth", $this->getHealth());
		$this->namedtag->putFloat("JumpStrength", $this->jumpStrength);
		$this->namedtag->putFloat("MovementSpeed", $this->movementSpeed);
	}

	protected function addAttributes(): void
	{
		$this->attributeMap->addAttribute(Attribute::getAttribute(Attribute::ABSORPTION));

		$this->attributeMap->addAttribute(
			Attribute::getAttribute(Attribute::HEALTH)
				->setMinValue(0)
				->setMaxValue(30)
		);
		$this->attributeMap->addAttribute(Attribute::getAttribute(Attribute::MOVEMENT_SPEED));
		$this->attributeMap->addAttribute(Attribute::getAttribute(Attribute::HORSE_JUMP_STRENGTH));

		parent::addAttributes();
	}

	public function getInventory(): HorseInventory
	{
		return $this->inventory;
	}

	public function setMaxHealth(int $amount)
	{
		parent::setMaxHealth($amount);

		$attr = $this->attributeMap->getAttribute(Attribute::HEALTH);
		if ($attr !== null) {
			$attr->setMaxValue($amount);

			if ($this->linkedEntity instanceof Player) {
				$this->syncAttributes($this->linkedEntity);
			}
		}
	}

	public function setHealth($amount)
	{
		parent::setHealth($amount);

		$attr = $this->attributeMap->getAttribute(Attribute::HEALTH);
		if ($attr !== null) {
			$attr->setValue($amount, true);

			if ($this->linkedEntity instanceof Player) {
				$this->syncAttributes($this->linkedEntity);
			}
		}
	}

	public function mount(Entity $rider): void
	{
		if ($this->linkedEntity !== null && $this->linkedEntity->isAlive()) return;

		if ($rider instanceof Player) {
			$this->syncAttributes($rider);
		}

		$rider->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_RIDING, true);
		$rider->setDataProperty(self::DATA_RIDER_SEAT_POSITION, self::DATA_TYPE_VECTOR3F, [0, 2.32001, -0.2]);

		$this->server->broadcastPacket(
			$this->getLevel()->getPlayers(),
			SetEntityLinkPacket::create(
				$this->getId(),
				$rider->getId(),
				SetEntityLinkPacket::TYPE_RIDE,
			),
		);
		$this->linkedEntity = $rider;
	}

	public function dismount(Entity $rider): void
	{
		$rider->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_RIDING, false);

		$this->server->broadcastPacket(
			$this->getLevel()->getPlayers(),
			SetEntityLinkPacket::create(
				$this->getId(),
				$rider->getId(),
				SetEntityLinkPacket::TYPE_REMOVE,
			)
		);
		$this->linkedEntity = null;
	}

	public function getMovementSpeed(): float
	{
		return $this->movementSpeed;
	}

	/**
	 * @param float $movementSpeed Value in 0.1125–0.3375 range
	 *
	 * @return void
	 */
	public function setMovementSpeed(float $movementSpeed): void
	{
		$this->movementSpeed = $movementSpeed;

		$attr = $this->attributeMap->getAttribute(Attribute::MOVEMENT_SPEED);
		if ($attr !== null) {
			$attr->setValue($movementSpeed, true);

			if ($this->linkedEntity instanceof Player) {
				$this->syncAttributes($this->linkedEntity);
			}
		}
	}

	public function getJumpStrength(): float
	{
		return $this->jumpStrength;
	}

	/**
	 * @param float $jumpStrength Value in 0.4–1.0 range
	 *
	 * @return void
	 */
	public function setJumpStrength(float $jumpStrength): void
	{
		$this->jumpStrength = $jumpStrength;
		$this->jumpVelocity = $jumpStrength;

		$attr = $this->attributeMap->getAttribute(Attribute::HORSE_JUMP_STRENGTH);
		if ($attr !== null) {
			$attr->setValue($jumpStrength, true);

			if ($this->linkedEntity instanceof Player) {
				$this->syncAttributes($this->linkedEntity);
			}
		}
	}

	public function isSaddled(): bool
	{
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SADDLED);
	}

	public function setSaddledDataFlags(bool $value)
	{
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_SADDLED, $value);
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_WASD_CONTROLLED, $value);
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_CAN_POWER_JUMP, $value);
	}

	public function isTamed(): bool
	{
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_TAMED);
	}

	public function setTamed(bool $value)
	{
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_TAMED, $value);
	}

	public function getVariant(): int
	{
		return $this->getDataProperty(self::DATA_VARIANT);
	}

	/**
	 * @param int $color {@see HorseVariant::ALLOWED_VARIANTS}
	 *
	 * @return void
	 */
	public function setVariant(int $color)
	{
		$this->setDataProperty(self::DATA_VARIANT, self::DATA_TYPE_INT, $color);
	}

	public function getName(): string
	{
		return "Horse";
	}

	public function spawnTo(Player $player): void
	{
		$player->dataPacket(
			AddEntityPacket::create(
				$this->getId(),
				self::NETWORK_ID,
				$this,
				$this->getMotion(),
				$this->yaw,
				$this->pitch,
				$this->dataProperties,
			),
		);

		$this->syncChestPlate([$player]);

		parent::spawnTo($player);
	}

	/**
	 * @return ItemItem[]
	 */
	public function getDrops(): array
	{
		$leathersCount = mt_rand(0, 2); // TODO: Check looting level
		$additionalItems = $leathersCount == 0
			? []
			: [ItemItem::get(ItemIds::LEATHER, $leathersCount)];

		return array_merge($this->inventory->getContents(), $additionalItems);
	}

	public function syncChestPlate(?array $players = null): void
	{
		$pk = new MobArmorEquipmentPacket();
		$pk->eid = $this->getId();
		$pk->slots = [
			ItemItem::get(0),
			ItemItem::get($this->getInventory()->getChestPlate()->getId()),
			ItemItem::get(0),
			ItemItem::get(0)
		];
		foreach ($players ?? $this->level->getPlayers() as $player) {
			$player->dataPacket($pk);
		}
	}

	private function syncAttributes(Player $player)
	{
		$entries = $this->attributeMap->getAll();
		if (count($entries) > 0) {
			$pk = new UpdateAttributesPacket();
			$pk->entityId = $this->id;
			$pk->entries = $entries;

			$player->dataPacket($pk);
		}
	}
}
