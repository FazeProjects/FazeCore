<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class ContainerClosePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CONTAINER_CLOSE_PACKET;

	public $windowid;

	/**
	 *
	 */
	public function decode(){
		$this->windowid = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->windowid);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "ContainerClosePacket";
	}

}