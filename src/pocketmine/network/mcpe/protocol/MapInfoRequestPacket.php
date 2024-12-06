<?php



namespace pocketmine\network\mcpe\protocol;

class MapInfoRequestPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::MAP_INFO_REQUEST_PACKET;

	public $uuid;

	/**
	 *
	 */
	public function decode(){
		$this->uuid = $this->getEntityId();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->uuid);
	}

}
