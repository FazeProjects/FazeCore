<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class ExplodePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::EXPLODE_PACKET;

	public $x;
	public $y;
	public $z;
	/** @var float */
	public $radius;
	/** @var Vector3[] */
	public $records = [];

	/**
	 * @return $this
	 */
	public function clean(){
		$this->records = [];
		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		/*$this->getVector3f($this->x, $this->y, $this->z);
		$this->radius = (float) ($this->getVarInt() / 32);
		$count = $this->getUnsignedVarInt();
		for($i = 0; $i < $count; ++$i){
			$x = $y = $z = null;
			$this->getSignedBlockCoords($x, $y, $z);
			$this->records[$i] = new Vector3($x, $y, $z);
		}*/
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putVarInt((int) ($this->radius * 32));
		$this->putUnsignedVarInt(count($this->records));
		if(count($this->records) > 0){
			foreach($this->records as $record){
				$this->putSignedBlockCoords((int) $record->x, (int) $record->y, (int) $record->z);
			}
		}
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "ExplodePacket";
	}

}