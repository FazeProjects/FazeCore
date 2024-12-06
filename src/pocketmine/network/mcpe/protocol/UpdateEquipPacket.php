<?php

namespace pocketmine\network\mcpe\protocol;

class UpdateEquipPacket extends DataPacket
{

	public const NETWORK_ID = ProtocolInfo::UPDATE_EQUIP_PACKET;

	public $windowId;
	public $windowType;
	public $unknownVarint = 0; //TODO: find out what this is (vanilla always sends 0)
	public $entityUniqueId;
	public $namedtag;

	/**
	 * @inheritDoc
	 */
	public function decode()
	{
		$this->windowId = $this->getByte();
		$this->windowType = $this->getByte();
		$this->unknownVarint = $this->getVarInt();
		$this->entityUniqueId = $this->getEntityId();
		$this->namedtag = $this->getRemaining();
	}

	/**
	 * @inheritDoc
	 */
	public function encode()
	{
		$this->reset();

		$this->putByte($this->windowId);
		$this->putByte($this->windowType);
		$this->putVarInt($this->unknownVarint);
		$this->putEntityId($this->entityUniqueId);
		$this->put($this->namedtag);
	}

	public static function create(int $eid, int $windowId, int $windowType, string $namedtag): UpdateEquipPacket
	{
		$pk = new UpdateEquipPacket();
		$pk->windowId = $windowId;
		$pk->entityUniqueId = $eid;
		$pk->windowType = $windowType;
		$pk->namedtag = $namedtag;

		return $pk;
	}
}