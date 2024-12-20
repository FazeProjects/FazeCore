<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\entity;


use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\DoubleTag;

abstract class Projectile extends Entity{

	const DATA_SHOOTER_ID = 17;

	/** @var float */
	protected $damage = 0.0;

	public $hadCollision = false;

	/**
	 * Projectile constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 * @param Entity|null $shootingEntity
	 */
	public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($level, $nbt);
		if($shootingEntity !== null){
			$this->setOwningEntity($shootingEntity);
		}
	}

	/**
	 * @param float             $damage
	 * @param EntityDamageEvent $source
	 *
	 * @return bool|void
	 */
	public function attack($damage, EntityDamageEvent $source){
		if($source->getCause() === EntityDamageEvent::CAUSE_VOID){
			parent::attack($damage, $source);
		}
	}

	protected function initEntity(){
		parent::initEntity();

		$this->setMaxHealth(1);
		$this->setHealth(1);
		if(isset($this->namedtag->Age)){
			$this->age = $this->namedtag["Age"];
		}

		if(isset($this->namedtag->damage)){
			$this->damage = $this->namedtag["damage"];
		}
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canCollideWith(Entity $entity){
		return $entity instanceof Living and !$this->onGround;
	}

	/**
	 * Returns the amount of damage this projectile will deal to the entity it hits.
	 * @return int
	 */
	public function getResultDamage() : int{
		return (int) ceil(sqrt($this->motionX ** 2 + $this->motionY ** 2 + $this->motionZ ** 2) * $this->damage);
	}

	public function onCollideWithEntity(Entity $entity){
		$this->server->getPluginManager()->callEvent(new ProjectileHitEvent($this));

		$damage = $this->getResultDamage();

		if($this->getOwningEntity() === null){
			$ev = new EntityDamageByEntityEvent($this, $entity, EntityDamageEvent::CAUSE_PROJECTILE, $damage);
		}else{
			$ev = new EntityDamageByChildEntityEvent($this->getOwningEntity(), $this, $entity, EntityDamageEvent::CAUSE_PROJECTILE, $damage);
		}

		if($entity->attack($ev->getFinalDamage(), $ev) === true){
			if($this instanceof Arrow and $this->getPotionId() != 0){
				foreach(Potion::getEffectsById($this->getPotionId() - 1) as $effect){
					$entity->addEffect($effect->setDuration($effect->getDuration() / 8));
				}
			}
			$ev->useArmors();
		}

		$this->hadCollision = true;

		if($this->fireTicks > 0){
			$ev = new EntityCombustByEntityEvent($this, $entity, 5);
			$this->server->getPluginManager()->callEvent($ev);
			if(!$ev->isCancelled()){
				$entity->setOnFire($ev->getDuration());
			}
		}

		$this->close();
	}

	public function canBeCollidedWith() : bool{
		return false;
	}

	/**
	 * Returns the base damage applied on collision. This is multiplied by the projectile's speed to give a result
	 * damage.
	 *
	 * @return float
	 */
	public function getBaseDamage() : float{
		return $this->damage;
	}

	/**
	 * Sets the base amount of damage applied by the projectile.
	 *
	 * @param float $damage
	 */
	public function setBaseDamage(float $damage) : void{
		$this->damage = $damage;
	}

	/**
	 * Called when the projectile hits something. Override this to perform non-target-specific effects when the
	 * projectile hits something.
	 */
	protected function onHit(ProjectileHitEvent $event) : void{

	}

	public function saveNBT(){
		parent::saveNBT();

		$this->namedtag->Age = new ShortTag("Age", $this->age);
		$this->namedtag->damage = new DoubleTag("damage", $this->damage);
	}
	
	protected function applyDragBeforeGravity() : bool{
		return true;
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

		if($this->isAlive()){
			$movingObjectPosition = null;

			$moveVector = new Vector3($this->x + $this->motionX, $this->y + $this->motionY, $this->z + $this->motionZ);

			$list = $this->getLevel()->getCollidingEntities($this->boundingBox->addCoord($this->motionX, $this->motionY, $this->motionZ)->expand(1, 1, 1), $this);

			$nearDistance = PHP_INT_MAX;
			$nearEntity = null;

			foreach($list as $entity){
				if(/*!$entity->canCollideWith($this) or */
				($entity->getId() === $this->getOwningEntityId() and $this->ticksLived < 5)
				){
					continue;
				}

				$axisalignedbb = $entity->boundingBox->expandedCopy(0.3, 0.3, 0.3);
				$rayTraceResult = $axisalignedbb->calculateIntercept($this, $moveVector);

				if($rayTraceResult === null){
					continue;
				}

				$distance = $this->distanceSquared($rayTraceResult->hitVector);

				if($distance < $nearDistance){
					$nearDistance = $distance;
					$nearEntity = $entity;
				}
			}

			if($nearEntity !== null){
				$this->onCollideWithEntity($nearEntity);
				return false;
			}

			if($this->isCollided and !$this->hadCollision){ //Collided with a block
				$this->hadCollision = true;

				$this->motionX = 0;
				$this->motionY = 0;
				$this->motionZ = 0;

				$this->server->getPluginManager()->callEvent(new ProjectileHitEvent($this));
				return false;
			}elseif(!$this->isCollided and $this->hadCollision){ //Previously collided with block, but block later removed
				$this->hadCollision = false;
			}

			if(!$this->hadCollision or abs($this->motionX) > self::MOTION_THRESHOLD or abs($this->motionY) > self::MOTION_THRESHOLD or abs($this->motionZ) > self::MOTION_THRESHOLD){
				$f = sqrt(($this->motionX ** 2) + ($this->motionZ ** 2));
				$this->yaw = (atan2($this->motionX, $this->motionZ) * 180 / M_PI);
				$this->pitch = (atan2($this->motionY, $f) * 180 / M_PI);
				$hasUpdate = true;
			}
		}

		return $hasUpdate;
	}

}