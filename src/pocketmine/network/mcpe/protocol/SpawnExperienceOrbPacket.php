<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SpawnExperienceOrbPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET;

	public $x;
	public $y;
	public $z;
	public $amount;

	/**
	 *
	 */
	public function decode(){
		$this->getVector3f($this->x, $this->y, $this->z);
		$this->amount = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putVarInt($this->amount);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SpawnExperienceOrbPacket";
	}

}
