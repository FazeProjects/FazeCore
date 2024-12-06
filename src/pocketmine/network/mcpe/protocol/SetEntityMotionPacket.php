<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SetEntityMotionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_ENTITY_MOTION_PACKET;

	public $eid;
	public $motionX;
	public $motionY;
	public $motionZ;

	/**
	 * @return $this
	 */
	public function clean(){
		$this->entities = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
        $this->eid = $this->getEntityId();
        $this->getVector3f($this->x, $this->y, $this->z);
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid);
		$this->putVector3f($this->motionX, $this->motionY, $this->motionZ);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "SetEntityMotionPacket";
	}

}
