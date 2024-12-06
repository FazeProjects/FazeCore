<?php

namespace pocketmine\network;

use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AddHangingEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\AddItemPacket;
use pocketmine\network\mcpe\protocol\AddPaintingPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\BlockPickRequestPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\CameraPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\ChunkRadiusUpdatedPacket;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\network\mcpe\protocol\ClientToServerHandshakePacket;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\CommandStepPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerSetContentPacket;
use pocketmine\network\mcpe\protocol\ContainerSetDataPacket;
use pocketmine\network\mcpe\protocol\ContainerSetSlotPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\CraftingEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\DisconnectPacket;
use pocketmine\network\mcpe\protocol\DropItemPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\EntityFallPacket;
use pocketmine\network\mcpe\protocol\ExplodePacket;
use pocketmine\network\mcpe\protocol\FullChunkDataPacket;
use pocketmine\network\mcpe\protocol\HurtArmorPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryActionPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\MapInfoRequestPacket;
use pocketmine\network\mcpe\protocol\MobArmorEquipmentPacket;
use pocketmine\network\mcpe\protocol\MobEffectPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\network\mcpe\protocol\RemoveBlockPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\ReplaceItemInSlotPacket;
use pocketmine\network\mcpe\protocol\RequestChunkRadiusPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\RespawnPacket;
use pocketmine\network\mcpe\protocol\RiderJumpPacket;
use pocketmine\network\mcpe\protocol\ServerToClientHandshakePacket;
use pocketmine\network\mcpe\protocol\SetCommandsEnabledPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\network\mcpe\protocol\SetEntityDataPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;
use pocketmine\network\mcpe\protocol\SetHealthPacket;
use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
use pocketmine\network\mcpe\protocol\SetSpawnPositionPacket;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\network\mcpe\protocol\SetTitlePacket;
use pocketmine\network\mcpe\protocol\ShowCreditsPacket;
use pocketmine\network\mcpe\protocol\SpawnExperienceOrbPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\UpdateEquipPacket;
use pocketmine\network\mcpe\protocol\UpdateTradePacket;
use pocketmine\network\mcpe\protocol\UseItemPacket;
use SplFixedArray;

class PacketPool
{

	/** @var SplFixedArray */
	private $packetPool;

	public function __construct()
	{
		$this->packetPool = new SplFixedArray(256);

		$this->register(ProtocolInfo::UPDATE_EQUIP_PACKET, UpdateEquipPacket::class);
		$this->register(ProtocolInfo::ADD_ENTITY_PACKET, AddEntityPacket::class);
		$this->register(ProtocolInfo::ADD_HANGING_ENTITY_PACKET, AddHangingEntityPacket::class);
		$this->register(ProtocolInfo::ADD_ITEM_ENTITY_PACKET, AddItemEntityPacket::class);
		$this->register(ProtocolInfo::ADD_ITEM_PACKET, AddItemPacket::class);
		$this->register(ProtocolInfo::ADD_PAINTING_PACKET, AddPaintingPacket::class);
		$this->register(ProtocolInfo::ADD_PLAYER_PACKET, AddPlayerPacket::class);
		$this->register(ProtocolInfo::ADVENTURE_SETTINGS_PACKET, AdventureSettingsPacket::class);
		$this->register(ProtocolInfo::ANIMATE_PACKET, AnimatePacket::class);
		$this->register(ProtocolInfo::AVAILABLE_COMMANDS_PACKET, AvailableCommandsPacket::class);
		$this->register(ProtocolInfo::BLOCK_ENTITY_DATA_PACKET, BlockEntityDataPacket::class);
		$this->register(ProtocolInfo::BLOCK_EVENT_PACKET, BlockEventPacket::class);
		$this->register(ProtocolInfo::BOSS_EVENT_PACKET, BossEventPacket::class);
		$this->register(ProtocolInfo::CAMERA_PACKET, CameraPacket::class);
		$this->register(ProtocolInfo::CHANGE_DIMENSION_PACKET, ChangeDimensionPacket::class);
		$this->register(ProtocolInfo::CHUNK_RADIUS_UPDATED_PACKET, ChunkRadiusUpdatedPacket::class);
		$this->register(ProtocolInfo::CLIENTBOUND_MAP_ITEM_DATA_PACKET, ClientboundMapItemDataPacket::class);
		$this->register(ProtocolInfo::CLIENT_TO_SERVER_HANDSHAKE_PACKET, ClientToServerHandshakePacket::class);
		$this->register(ProtocolInfo::COMMAND_STEP_PACKET, CommandStepPacket::class);
		$this->register(ProtocolInfo::CONTAINER_CLOSE_PACKET, ContainerClosePacket::class);
		$this->register(ProtocolInfo::CONTAINER_OPEN_PACKET, ContainerOpenPacket::class);
		$this->register(ProtocolInfo::CONTAINER_SET_CONTENT_PACKET, ContainerSetContentPacket::class);
		$this->register(ProtocolInfo::CONTAINER_SET_DATA_PACKET, ContainerSetDataPacket::class);
		$this->register(ProtocolInfo::CONTAINER_SET_SLOT_PACKET, ContainerSetSlotPacket::class);
		$this->register(ProtocolInfo::CRAFTING_DATA_PACKET, CraftingDataPacket::class);
		$this->register(ProtocolInfo::CRAFTING_EVENT_PACKET, CraftingEventPacket::class);
		$this->register(ProtocolInfo::DISCONNECT_PACKET, DisconnectPacket::class);
		$this->register(ProtocolInfo::DROP_ITEM_PACKET, DropItemPacket::class);
		$this->register(ProtocolInfo::ENTITY_EVENT_PACKET, EntityEventPacket::class);
		$this->register(ProtocolInfo::EXPLODE_PACKET, ExplodePacket::class);
		$this->register(ProtocolInfo::FULL_CHUNK_DATA_PACKET, FullChunkDataPacket::class);
		$this->register(ProtocolInfo::HURT_ARMOR_PACKET, HurtArmorPacket::class);
		$this->register(ProtocolInfo::INTERACT_PACKET, InteractPacket::class);
		$this->register(ProtocolInfo::INVENTORY_ACTION_PACKET, InventoryActionPacket::class);
		$this->register(ProtocolInfo::ITEM_FRAME_DROP_ITEM_PACKET, ItemFrameDropItemPacket::class);
		$this->register(ProtocolInfo::LEVEL_EVENT_PACKET, LevelEventPacket::class);
		$this->register(ProtocolInfo::LEVEL_SOUND_EVENT_PACKET, LevelSoundEventPacket::class);
		$this->register(ProtocolInfo::LOGIN_PACKET, LoginPacket::class);
		$this->register(ProtocolInfo::MAP_INFO_REQUEST_PACKET, MapInfoRequestPacket::class);
		$this->register(ProtocolInfo::MOB_ARMOR_EQUIPMENT_PACKET, MobArmorEquipmentPacket::class);
		$this->register(ProtocolInfo::MOB_EFFECT_PACKET, MobEffectPacket::class);
		$this->register(ProtocolInfo::MOB_EQUIPMENT_PACKET, MobEquipmentPacket::class);
		$this->register(ProtocolInfo::MOVE_ENTITY_PACKET, MoveEntityPacket::class);
		$this->register(ProtocolInfo::MOVE_PLAYER_PACKET, MovePlayerPacket::class);
		$this->register(ProtocolInfo::ENTITY_FALL_PACKET, EntityFallPacket::class);
		$this->register(ProtocolInfo::PLAYER_ACTION_PACKET, PlayerActionPacket::class);
		$this->register(ProtocolInfo::PLAYER_INPUT_PACKET, PlayerInputPacket::class);
		$this->register(ProtocolInfo::PLAYER_LIST_PACKET, PlayerListPacket::class);
		$this->register(ProtocolInfo::PLAY_STATUS_PACKET, PlayStatusPacket::class);
		$this->register(ProtocolInfo::REMOVE_BLOCK_PACKET, RemoveBlockPacket::class);
		$this->register(ProtocolInfo::REMOVE_ENTITY_PACKET, RemoveEntityPacket::class);
		$this->register(ProtocolInfo::REPLACE_ITEM_IN_SLOT_PACKET, ReplaceItemInSlotPacket::class);
		$this->register(ProtocolInfo::REQUEST_CHUNK_RADIUS_PACKET, RequestChunkRadiusPacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACKS_INFO_PACKET, ResourcePacksInfoPacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACK_CHUNK_REQUEST_PACKET, ResourcePackChunkRequestPacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACK_CHUNK_DATA_PACKET, ResourcePackChunkDataPacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACK_CLIENT_RESPONSE_PACKET, ResourcePackClientResponsePacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACK_DATA_INFO_PACKET, ResourcePackDataInfoPacket::class);
		$this->register(ProtocolInfo::RESOURCE_PACK_STACK_PACKET, ResourcePackStackPacket::class);
		$this->register(ProtocolInfo::RESPAWN_PACKET, RespawnPacket::class);
		$this->register(ProtocolInfo::RIDER_JUMP_PACKET, RiderJumpPacket::class);
		$this->register(ProtocolInfo::SHOW_CREDITS_PACKET, ShowCreditsPacket::class);
		$this->register(ProtocolInfo::SERVER_TO_CLIENT_HANDSHAKE_PACKET, ServerToClientHandshakePacket::class);
		$this->register(ProtocolInfo::SET_COMMANDS_ENABLED_PACKET, SetCommandsEnabledPacket::class);
		$this->register(ProtocolInfo::SET_DIFFICULTY_PACKET, SetDifficultyPacket::class);
		$this->register(ProtocolInfo::SET_ENTITY_DATA_PACKET, SetEntityDataPacket::class);
		$this->register(ProtocolInfo::SET_ENTITY_LINK_PACKET, SetEntityLinkPacket::class);
		$this->register(ProtocolInfo::SET_ENTITY_MOTION_PACKET, SetEntityMotionPacket::class);
		$this->register(ProtocolInfo::SET_HEALTH_PACKET, SetHealthPacket::class);
		$this->register(ProtocolInfo::SET_PLAYER_GAME_TYPE_PACKET, SetPlayerGameTypePacket::class);
		$this->register(ProtocolInfo::SET_SPAWN_POSITION_PACKET, SetSpawnPositionPacket::class);
		$this->register(ProtocolInfo::SET_TIME_PACKET, SetTimePacket::class);
		$this->register(ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET, SpawnExperienceOrbPacket::class);
		$this->register(ProtocolInfo::START_GAME_PACKET, StartGamePacket::class);
		$this->register(ProtocolInfo::TAKE_ITEM_ENTITY_PACKET, TakeItemEntityPacket::class);
		$this->register(ProtocolInfo::TEXT_PACKET, TextPacket::class);
		$this->register(ProtocolInfo::TRANSFER_PACKET, TransferPacket::class);
		$this->register(ProtocolInfo::UPDATE_ATTRIBUTES_PACKET, UpdateAttributesPacket::class);
		$this->register(ProtocolInfo::UPDATE_BLOCK_PACKET, UpdateBlockPacket::class);
		$this->register(ProtocolInfo::UPDATE_TRADE_PACKET, UpdateTradePacket::class);
		$this->register(ProtocolInfo::USE_ITEM_PACKET, UseItemPacket::class);
		$this->register(ProtocolInfo::BLOCK_PICK_REQUEST_PACKET, BlockPickRequestPacket::class);
		$this->register(ProtocolInfo::COMMAND_BLOCK_UPDATE_PACKET, CommandBlockUpdatePacket::class);
		$this->register(ProtocolInfo::PLAY_SOUND_PACKET, PlaySoundPacket::class);
		$this->register(ProtocolInfo::SET_TITLE_PACKET, SetTitlePacket::class);
		$this->register(ProtocolInfo::STOP_SOUND_PACKET, StopSoundPacket::class);

		$this->register(0xfe, BatchPacket::class);
	}

	/**
	 * @param int $id 0-255
	 * @param string $class
	 */
	public function register(int $id, string $class)
	{
		$this->packetPool[$id] = new $class;
	}

	/**
	 * @param $id
	 *
	 * @return DataPacket
	 */
	public function get($id): ?DataPacket
	{
		/** @var DataPacket $class */
		$class = $this->packetPool[$id];
		if ($class !== null) {
			return clone $class;
		}

		return null;
	}
}