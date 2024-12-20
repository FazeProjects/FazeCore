<?php

namespace pocketmine\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Projectile;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\nbt\tag\Compound;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class SplashPotion extends Projectile
{
    const NETWORK_ID = 86;

    public $width = 0.25;
    public $height = 0.25;

    protected $drag = 0.01;
    protected $gravity = 0.05;

    public function __construct(FullChunk $chunk, Compound $nbt, Entity $shootingEntity = null, int $potionId = 0)
    {
        parent::__construct($chunk, $nbt, $shootingEntity);

        $this->setPotionId($potionId);
    }

    public function setPotionId(int $meta)
    {
        $this->setDataProperty(self::DATA_POTION_AUX_VALUE, self::DATA_TYPE_SHORT, $meta);
    }

    public function getPotionId(): int
    {
        return $this->getDataProperty(self::DATA_POTION_AUX_VALUE) ?? 0;
    }

    public function onUpdate($currentTick)
    {
        if ($this->closed) {
            return false;
        }

        //$this->timings->startTiming();

        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->onGround || $this->hadCollision) {
            if($this->shootingEntity instanceof Player){
                $this->shootingEntity->sendSound("SOUND_SPLASH", ['x' => $this->getX(), 'y' => $this->getY(), 'z' => $this->getZ()] ,EntityIds::ID_NONE, -1 ,$this->getViewers());
            }

            $color = \pocketmine\item\SplashPotion::getColor($this->getPotionId());

            $pk = new LevelEventPacket;
            $pk->evid = LevelEventPacket::EVENT_PARTICLE_SPLASH;
            $pk->evid = LevelSoundEventPacket::SOUND_SPLASH;
            $pk->x = $this->x;
            $pk->y = $this->y;
            $pk->z = $this->z;
            $pk->data = ($color[0] << 16) + ($color[1] << 8) + $color[2];
            Server::broadcastPacket($this->getViewers(), $pk);

            foreach ($this->level->getNearbyEntities($this->boundingBox->grow(4, 4, 4), $this) as $entity) { //todo: someone has to check this https://minecraft.gamepedia.com/Splash_Potion
                if ($entity->distanceSquared($this) <= 16) {
                    foreach (\pocketmine\item\SplashPotion::getEffectsById($this->getPotionId()) as $effect) {
                        $entity->addEffect($effect);
                    }
                }
            }

            $this->kill();
        }

        return $hasUpdate;
    }

    public function spawnTo(Player $player)
    {
        if (!isset($this->hasSpawned[$player->getId()]) && isset($player->usedChunks[Level::chunkHash($this->chunk->getX(), $this->chunk->getZ())])) {
            $this->hasSpawned[$player->getId()] = $player;
            $pk = new AddEntityPacket();
            $pk->type = self::NETWORK_ID;
            $pk->eid = $this->getId();
            $pk->x = $this->x;
            $pk->y = $this->y;
            $pk->z = $this->z;
            $pk->speedX = $this->motionX;
            $pk->speedY = $this->motionY;
            $pk->speedZ = $this->motionZ;
            $pk->metadata = $this->dataProperties;
            $player->dataPacket($pk);
        }
    }

}