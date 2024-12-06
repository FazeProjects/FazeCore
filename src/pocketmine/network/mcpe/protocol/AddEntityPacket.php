<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

#ifndef COMPILE

use pocketmine\entity\Attribute;
use pocketmine\math\Vector3;

#endif

class AddEntityPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADD_ENTITY_PACKET;

	public $eid;
	public $type;
	public $x;
	public $y;
	public $z;
	public $speedX = 0.0;
	public $speedY = 0.0;
	public $speedZ = 0.0;
	public $yaw = 0.0;
	public $pitch = 0.0;
	/** @var Attribute[] */
	public $attributes = [];
	public $metadata = [];
	public $links = [];

	/**
	 *
	 */
	public function decode(){
        $this->eid = $this->getEntityId();
        $this->eid = $this->getEntityId();
        $this->type = $this->getUnsignedVarInt();
        $this->getVector3f($this->x, $this->y, $this->z);
        $this->getVector3f($this->speedX, $this->speedY, $this->speedZ);
        $this->pitch = $this->getLFloat();
        $this->yaw = $this->getLFloat();

        $count = $this->getUnsignedVarInt();
        for($i = 0; $i < $count && !$this->feof(); ++$i){
            $name = $this->getString();
            if(($attr = Attribute::getAttributeByName($name)) !== null){
                $this->attributes[] = new Attribute(
                    $attr->getId(), //todo fuck this
                    $name,
                    $this->getLFloat(),
                    $this->getLFloat(),
                    $this->getLFloat()
                );
            }else{
                $this->attributes[] = [
                    $name,
                    $this->getLFloat(),
                    $this->getLFloat(),
                    $this->getLFloat()
                ];
            }
        }

        $this->metadata = $this->getEntityMetadata(true);

        $count = $this->getUnsignedVarInt();
        for($i = 0; $i < $count && !$this->feof(); ++$i){
            $this->links[] = [
                $this->getEntityId(),
                $this->getEntityId(),
                $this->getByte()
            ];
        }
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid); //EntityUniqueID - TODO: verify this
		$this->putEntityId($this->eid);
		$this->putUnsignedVarInt($this->type);
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putVector3f($this->speedX, $this->speedY, $this->speedZ);
		$this->putLFloat($this->pitch);
		$this->putLFloat($this->yaw);
		$this->putUnsignedVarInt(count($this->attributes));
		foreach($this->attributes as $entry){
			$this->putString($entry->getName());
			$this->putLFloat($entry->getMinValue());
			$this->putLFloat($entry->getValue());
			$this->putLFloat($entry->getMaxValue());
		}
		$this->putEntityMetadata($this->metadata);
		$this->putUnsignedVarInt(count($this->links));
		foreach($this->links as $link){
			$this->putEntityId($link[0]);
			$this->putEntityId($link[1]);
			$this->putByte($link[2]);
		}
	}

	/**
	 * @return AddEntityPacket|string
	 */
	public function getName(){
		return "AddEntityPacket";
	}
	
	public static function create(
		int     $eid,
		int     $networkId,
		Vector3 $position,
		Vector3 $speed,
		float   $yaw,
		float   $pitch,
		array   $metadata
	) : AddEntityPacket
	{
		$pk = new AddEntityPacket();
		$pk->eid = $eid;
		$pk->type = $networkId;
		$pk->x = $position->x;
		$pk->y = $position->y;
		$pk->z = $position->z;
		$pk->speedX = $speed->x;
		$pk->speedY = $speed->y;
		$pk->speedZ = $speed->z;
		$pk->yaw = $yaw;
		$pk->pitch = $pitch;
		$pk->metadata = $metadata;

		return $pk;
	}
}
