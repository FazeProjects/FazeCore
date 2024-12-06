<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetSpawnPositionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_SPAWN_POSITION_PACKET;

	const TYPE_PLAYER_SPAWN = 0;
	const TYPE_WORLD_SPAWN = 1;

	public $spawnType;
	public $x;
	public $y;
	public $z;
	public $spawnForced;

	/**
	 *
	 */
	public function decode(){

	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->spawnType);
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putBool($this->spawnForced);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetSpawnPositionPacket";
	}

}
