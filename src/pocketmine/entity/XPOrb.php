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
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\event\player\PlayerPickupExpOrbEvent;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;

class XPOrb extends Entity {
	const NETWORK_ID = 69;

	/**
	 * Max distance an orb will follow a player across.
	 */
	public const MAX_TARGET_DISTANCE = 8.0;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.04;
	protected $drag = 0.02;

	protected $experience = 0;

	protected $range = 6;

	/**
	 * @var int
	 * Ticker used for determining interval in which to look for new target players.
	 */
	protected $lookForTargetTime = 0;

	/**
	 * @var int|null
	 * Runtime entity ID of the player this XP orb is targeting.
	 */
	protected $targetPlayerRuntimeId = null;

	public function initEntity(){
		parent::initEntity();
		if(isset($this->namedtag->Experience)){
			$this->experience = $this->namedtag["Experience"];
		}else $this->close();
	}

	public function hasTargetPlayer() : bool{
		return $this->targetPlayerRuntimeId !== null;
	}

	public function getTargetPlayer() : ?Human{
		if($this->targetPlayerRuntimeId === null){
			return null;
		}

		$entity = $this->level->getEntity($this->targetPlayerRuntimeId);
		if($entity instanceof Human){
			return $entity;
		}

		return null;
	}

	public function setTargetPlayer(?Human $player) : void{
		$this->targetPlayerRuntimeId = $player ? $player->getId() : null;
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

		$this->age += $tickDiff;
		if($this->age > 6000){
			$this->kill();
			$this->close();
			return true;
		}

		$currentTarget = $this->getTargetPlayer();
		if($currentTarget !== null and (!$currentTarget->isAlive() or $currentTarget->distanceSquared($this) > self::MAX_TARGET_DISTANCE ** 2)){
			$currentTarget = null;
		}

		if($this->lookForTargetTime >= 20){
			if($currentTarget === null){
				$newTarget = $this->level->getNearestEntity($this, self::MAX_TARGET_DISTANCE, Human::class);

				if($newTarget instanceof Human and !($newTarget instanceof Player and $newTarget->isSpectator())){
					$currentTarget = $newTarget;
				}
			}

			$this->lookForTargetTime = 0;
		}else{
			$this->lookForTargetTime += $tickDiff;
		}

		$this->setTargetPlayer($currentTarget);

		if($currentTarget !== null){
			$vector = $currentTarget->add(0, $currentTarget->getEyeHeight() / 2, 0)->subtract($this)->divide(self::MAX_TARGET_DISTANCE);

		    $distance = $vector->lengthSquared();
			if($distance < 1){
				$diff = $vector->normalize()->multiply(0.2 * (1 - sqrt($distance)) ** 2);

				$this->motionX += $diff->x;
				$this->motionY += $diff->y;
				$this->motionZ += $diff->z;
			}

			if($this->getLevel()->getServer()->expEnabled and $currentTarget->canPickupXp() and $this->boundingBox->intersectsWith($currentTarget->getBoundingBox())){
				$this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new PlayerPickupExpOrbEvent($currentTarget, $this->getExperience()));
				if(!$ev->isCancelled()){
					$this->kill();
					$this->close();
					if($this->getExperience() > 0){
						$this->level->broadcastLevelEvent($this, LevelEventPacket::EVENT_SOUND_ORB, mt_rand());
						$currentTarget->addXp($this->getExperience());
						$currentTarget->resetXpCooldown();

						//TODO: check Mending enchantment
					}
				}
			}
		}

		return $hasUpdate;
	}

	protected function tryChangeMovement(){
		$this->checkObstruction($this->x, $this->y, $this->z);
		parent::tryChangeMovement();
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canCollideWith(Entity $entity){
		return false;
	}

	public function canBeCollidedWith() : bool{
		return false;
	}

	/**
	 * @param $exp
	 */
	public function setExperience($exp){
		$this->experience = $exp;
	}

	/**
	 * @return int
	 */
	public function getExperience(){
		return $this->experience;
	}

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_NO_AI, true);
		$pk = new AddEntityPacket();
		$pk->type = XPOrb::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
