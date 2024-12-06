<?php



#include <rules/DataPacket.h>
namespace pocketmine\network\mcpe\protocol;


class CameraPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CAMERA_PACKET;
	public $eid;

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
		$this->putVarInt($this->eid);
		$this->putVarInt($this->eid);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "CameraPacket";
	}

}
