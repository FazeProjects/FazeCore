<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\event\player\PlayerFishEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\Player;


class FishingHook extends Projectile {
    const NETWORK_ID = 77;

    public $width = 0.2;
    public $length = 0.2;
    public $height = 0.2;

    protected $gravity = 0.07;
    protected $drag = 0.05;

    public $data = 0;
    public $attractTimer = 100;
    public $coughtTimer = 0;
    public $damageRod = false;

    public function initEntity(){
        parent::initEntity();

        if(isset($this->namedtag->Data)){
            $this->data = $this->namedtag["Data"];
        }
    }

    /**
     * FishingHook constructor.
     *
     * @param Level       $level
     * @param CompoundTag $nbt
     * @param Entity|null $shootingEntity
     */
    public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null){
        parent::__construct($level, $nbt, $shootingEntity);
    }

    /**
     * @param $id
     */
    public function setData($id){
        $this->data = $id;
    }

    /**
     * @return int
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param $tickDiff
     *
     * @return bool
     */
    public function entityBaseTick($tickDiff = 1){
        if($this->closed){
            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);

        if($this->isCollidedVertically && $this->isInsideOfWater()){
            $this->motionX = 0;
            $this->motionY += 0.01;
            $this->motionZ = 0;
            $this->motionChanged = true;
            $hasUpdate = true;
        }elseif($this->isCollided && $this->keepMovement === true){
            $this->motionX = 0;
            $this->motionY = 0;
            $this->motionZ = 0;
            $this->motionChanged = true;
            $this->keepMovement = false;
            $hasUpdate = true;
        }
        if($this->attractTimer === 0 && mt_rand(0, 100) <= 30){ // chance, that a fish bites
            $this->coughtTimer = mt_rand(5, 10) * 20; // random delay to catch fish
            $this->attractTimer = mt_rand(30, 100) * 20; // reset timer
            $this->attractFish();
            if($this->getOwningEntity() instanceof Player) $this->getOwningEntity()->sendTip("A fish bites!");
        }elseif($this->attractTimer > 0){
            $this->attractTimer--;
        }
        if($this->coughtTimer > 0){
            $this->coughtTimer--;
            $this->fishBites();
        }

        return $hasUpdate;
    }

    public function fishBites(){
        if($this->getOwningEntity() instanceof Player){
            $pk = new EntityEventPacket();
            $pk->eid = $this->getOwningEntity()->getId();//$this or $this->getOwningEntity()
            $pk->event = EntityEventPacket::FISH_HOOK_HOOK;
            $this->server->broadcastPacket($this->getOwningEntity()->hasSpawned, $pk);
        }
    }

    public function attractFish(){
        if($this->getOwningEntity() instanceof Player){
            $pk = new EntityEventPacket();
            $pk->eid = $this->getOwningEntity()->getId();//$this or $this->getOwningEntity()
            $pk->event = EntityEventPacket::FISH_HOOK_BUBBLE;
            $this->server->broadcastPacket($this->getOwningEntity()->hasSpawned, $pk);
        }
    }

    public function onCollideWithEntity(\pocketmine\entity\Entity $entityPlayer){
        parent::onCollideWithEntity($entityPlayer); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     */
    public function reelLine(){
        $this->damageRod = false;

        if($this->getOwningEntity() instanceof Player && $this->coughtTimer > 0){
            $fishes = [ItemItem::RAW_FISH, ItemItem::RAW_SALMON, ItemItem::CLOWN_FISH, ItemItem::PUFFER_FISH];
            $fish = array_rand($fishes, 1);
            $item = ItemItem::get($fishes[$fish]);
            $this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new PlayerFishEvent($this->getOwningEntity(), $item, $this));
            if(!$ev->isCancelled()){
                $this->getOwningEntity()->getInventory()->addItem($item);
                $this->getOwningEntity()->addXp(mt_rand(1, 6));
                $this->damageRod = true;
            }
        }

        if($this->getOwningEntity() instanceof Player){
            $this->getOwningEntity()->unlinkHookFromPlayer();
        }

        if(!$this->closed){
            $this->kill();
            $this->close();
        }

        return $this->damageRod;
    }

    /**
     * @param Player $player
     */
    public function spawnTo(Player $player){
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = FishingHook::NETWORK_ID;
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);

        parent::spawnTo($player);
    }
}