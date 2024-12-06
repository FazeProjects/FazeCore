<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\item\Item;

class ContainerSetContentPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CONTAINER_SET_CONTENT_PACKET;

	const SPECIAL_INVENTORY = 0;
	const SPECIAL_OFFHAND = 0x77;
	const SPECIAL_ARMOR = 0x78;
	const SPECIAL_CREATIVE = 0x79;
	const SPECIAL_HOTBAR = 0x7a;
	const SPECIAL_FIXED_INVENTORY = 0x7b;

	public $windowid;
	public $targetEid;
	/** @var Item[] */
	public $slots = [];
	/** @var Item[] */
	public $hotbar = [];

	/**
	 * @return $this
	 */
	public function clean(){
		$this->slots = [];
		$this->hotbar = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		$this->windowid = $this->getUnsignedVarInt();
		$this->targetEid = $this->getEntityId();
		$count = $this->getUnsignedVarInt();
		for($s = 0; $s < $count and !$this->feof(); ++$s){
			$this->slots[$s] = $this->getSlot();
		}
		
		$hotbarCount = $this->getUnsignedVarInt(); //MCPE always sends this, even when it's not a player inventory
		for($s = 0; $s < $hotbarCount and !$this->feof(); ++$s){
			$this->hotbar[$s] = $this->getVarInt();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putUnsignedVarInt($this->windowid);
		$this->putEntityId($this->targetEid);
		$this->putUnsignedVarInt(count($this->slots));
		foreach($this->slots as $slot){
			$this->putSlot($slot);
		}
		if($this->windowid === self::SPECIAL_INVENTORY and count($this->hotbar) > 0){
			$this->putUnsignedVarInt(count($this->hotbar));
			foreach($this->hotbar as $slot){
				$this->putVarInt($slot);
			}
		}else{
			$this->putUnsignedVarInt(0);
		}
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "ContainerSetContentPacket";
	}

}