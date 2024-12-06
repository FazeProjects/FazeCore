<?php
/*   __________________________________________________
    |          LunCore 1.1.2-private release           |
    |                                                  |
    |           Группа вк - vk.com/LunCore             |
    |__________________________________________________|
*/
 namespace pocketmine\entity; use pocketmine\item\Item as ItemItem; use pocketmine\level\Level; use pocketmine\nbt\tag\ByteTag; use pocketmine\nbt\tag\CompoundTag; use pocketmine\network\mcpe\protocol\AddEntityPacket; use pocketmine\Player; class Villager extends Creature implements NPC, Ageable { const PROFESSION_FARMER = 0; const NETWORK_ID = 15; const DATA_PROFESSION_ID = 16; public $width = 0.6; public $length = 0.6; public $height = 1.8; public function getName() : string { return "Villager"; } public function __construct(Level $level, CompoundTag $nbt) { goto nsjkz; lwyXX: $this->setDataProperty(self::DATA_PROFESSION_ID, self::DATA_TYPE_BYTE, $this->getProfession()); goto Eybx3; ibytY: AKbDX: goto XlTEq; XlTEq: parent::__construct($level, $nbt); goto lwyXX; IQy0V: $nbt->Profession = new ByteTag("Profession", mt_rand(0, 4)); goto ibytY; nsjkz: if (isset($nbt->Profession)) { goto AKbDX; } goto IQy0V; Eybx3: } protected function initEntity() { goto qNAmi; qNAmi: parent::initEntity(); goto eLHA4; qJV2T: $this->setProfession(self::PROFESSION_FARMER); goto WC3OQ; WC3OQ: j1uAZ: goto ftsOl; eLHA4: if (isset($this->namedtag->Profession)) { goto j1uAZ; } goto qJV2T; ftsOl: } public function spawnTo(Player $player) { goto WHn4e; ZtA7L: $pk->pitch = $this->pitch; goto ITY38; OjoiR: $pk->z = $this->z; goto XgzCG; yhlBt: $pk->speedZ = $this->motionZ; goto W95h8; OYcmy: $player->dataPacket($pk); goto klARD; twp_o: $pk->y = $this->y; goto OjoiR; klARD: parent::spawnTo($player); goto Kn93x; nl1hA: $pk->eid = $this->getId(); goto eezli; w4AyU: $pk->speedY = $this->motionY; goto yhlBt; eezli: $pk->type = Villager::NETWORK_ID; goto EoOfn; ITY38: $pk->metadata = $this->dataProperties; goto OYcmy; W95h8: $pk->yaw = $this->yaw; goto ZtA7L; EoOfn: $pk->x = $this->x; goto twp_o; XgzCG: $pk->speedX = $this->motionX; goto w4AyU; WHn4e: $pk = new AddEntityPacket(); goto nl1hA; Kn93x: } public function setProfession(int $profession) { $this->namedtag->Profession = new ByteTag("Profession", $profession); } public function getProfession() : int { $pro = (int) $this->namedtag["Profession"]; return min(4, max(0, $pro)); } public function isBaby() { return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BABY); } }