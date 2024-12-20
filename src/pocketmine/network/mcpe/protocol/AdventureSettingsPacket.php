<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class AdventureSettingsPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADVENTURE_SETTINGS_PACKET;

	const PERMISSION_NORMAL = 0;
	const PERMISSION_OPERATOR = 1;
	const PERMISSION_HOST = 2;
	const PERMISSION_AUTOMATION = 3;
	const PERMISSION_ADMIN = 4;

	public $worldImmutable = false;
	public $noPvp = false;
	public $noPvm = false;
	public $noMvp = false;

	public $autoJump = false;
	public $allowFlight = false;
	public $noClip = false;
	public $worldBuilder = false;
	public $isFlying = false;
	public $muted = false;

	public $flags = 0;
	public $userPermission;

	/**
	 *
	 */
	public function decode(){
		$this->flags = $this->getUnsignedVarInt();
		$this->userPermission = $this->getUnsignedVarInt();

		$this->worldImmutable = (bool) ($this->flags & 1);
		$this->noPvp = (bool) ($this->flags & (1 << 1));
		$this->noPvm = (bool) ($this->flags & (1 << 2));
		$this->noMvp = (bool) ($this->flags & (1 << 3));

		$this->autoJump = (bool) ($this->flags & (1 << 5));
		$this->allowFlight = (bool) ($this->flags & (1 << 6));
		$this->noClip = (bool) ($this->flags & (1 << 7));
		$this->worldBuilder = (bool) ($this->flags & (1 << 8));
		$this->isFlying = (bool) ($this->flags & (1 << 9));
		$this->muted = (bool) ($this->flags & (1 << 10));
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();

		$this->flags |= ((int) $this->worldImmutable);
		$this->flags |= ((int) $this->noPvp) << 1;
		$this->flags |= ((int) $this->noPvm) << 2;
		$this->flags |= ((int) $this->noMvp) << 3;

		$this->flags |= ((int) $this->autoJump) << 5;
		$this->flags |= ((int) $this->allowFlight) << 6;
		$this->flags |= ((int) $this->noClip) << 7;
		$this->flags |= ((int) $this->worldBuilder) << 8;
		$this->flags |= ((int) $this->isFlying) << 9;
		$this->flags |= ((int) $this->muted) << 10;

		$this->putUnsignedVarInt($this->flags);
		$this->putUnsignedVarInt($this->userPermission);
	}

}