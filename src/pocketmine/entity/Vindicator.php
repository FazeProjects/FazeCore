<?php
/*   __________________________________________________
    |          LunCore 1.1.2-private release           |
    |                                                  |
    |           Группа вк - vk.com/LunCore             |
    |__________________________________________________|
*/
 namespace pocketmine\entity; use pocketmine\item\Item as ItemItem; use pocketmine\network\mcpe\protocol\AddEntityPacket; use pocketmine\Player; class Vindicator extends Monster { const NETWORK_ID = 57; public $width = 0.6; public $length = 0.6; public $height = 0; public $dropExp = [5, 5]; public function getName() { return "Vindicator"; } public function initEntity() { $this->setMaxHealth(24); parent::initEntity(); } public function spawnTo(Player $player) { goto XU53_; A6IMS: $pk->pitch = $this->pitch; goto qQQHA; xdeJK: $pk->speedZ = $this->motionZ; goto dUiFI; y_ORG: $pk->eid = $this->getId(); goto CFJWP; dUiFI: $pk->yaw = $this->yaw; goto A6IMS; dWsZH: $pk->y = $this->y; goto I5vWI; i1so3: $player->dataPacket($pk); goto Q40q0; Q40q0: parent::spawnTo($player); goto sesTv; CFJWP: $pk->type = Vindicator::NETWORK_ID; goto qzp2d; qzp2d: $pk->x = $this->x; goto dWsZH; qQQHA: $pk->metadata = $this->dataProperties; goto i1so3; p4W1s: $pk->speedX = $this->motionX; goto LM1YZ; I5vWI: $pk->z = $this->z; goto p4W1s; XU53_: $pk = new AddEntityPacket(); goto y_ORG; LM1YZ: $pk->speedY = $this->motionY; goto xdeJK; sesTv: } public function getDrops() { $drops = [ItemItem::get(ItemItem::EMERALD, 0, mt_rand(0, 1))]; return $drops; } }
