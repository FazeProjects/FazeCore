<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class MoveEntityPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::MOVE_ENTITY_PACKET;

	/** @var int */
	public $eid;

	public $x;
	public $y;
	public $z;

	/** @var float */
	public $yaw;
	/** @var float */
	public $headYaw;
	/** @var float */
	public $pitch;
	/** @var bool */
	public $onGround = false;
	/** @var bool */
	public $teleported = false;

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityId();
		$this->getVector3f($this->x, $this->y, $this->z);
		$this->pitch = $this->getByteRotation();
		$this->headYaw = $this->getByteRotation();
		$this->yaw = $this->getByteRotation();
		$this->onGround = $this->getBool();
		$this->teleported = $this->getBool();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid);
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putByteRotation($this->pitch);
		$this->putByteRotation($this->headYaw);
		$this->putByteRotation($this->yaw);
		$this->putBool($this->onGround);
		$this->putBool($this->teleported);
	}

}
