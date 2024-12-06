<?php

/**
 * Full updated class 27-06-2023
 */

namespace pocketmine\entity;

use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\MobSpellParticle;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\item\Bow;

class Arrow extends Projectile {
	const NETWORK_ID = 80;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.05;
	protected $drag = 0.01;

	protected $damage = 2.0;

	protected $potionId;

	protected $bow;

	/**
	 * Arrow constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 * @param Entity|null $shootingEntity
	 * @param bool        $critical
	 */
	public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null, bool $critical = false, Bow $bow = null){
		if(!isset($nbt->Potion)){
			$nbt->Potion = new ShortTag("Potion", 0);
		}
		parent::__construct($level, $nbt, $shootingEntity);
		$this->potionId = $this->namedtag["Potion"];
		$this->setCritical($critical);
		$this->bow = $bow;
	}

	public function getBow() : ?Bow{
		return $this->bow;
	}

	public function setBow(?Bow $bow){
		$this->bow = $bow;
	}

	/**
	 * @return bool
	 */
	public function isCritical() : bool{
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_CRITICAL);
	}

	/**
	 * @return void
	 */
	public function setCritical(bool $value = true) : void{
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_CRITICAL, $value);
	}

	/**
	 * @return int
	 */
	public function getResultDamage() : int{
		$base = parent::getResultDamage();
		if($this->isCritical()){
			return ($base + mt_rand(0, (int) ($base / 2) + 1));
		}else{
			return $base;
		}
	}

	/**
	 * @return int
	 */
	public function getPotionId() : int{
		return $this->potionId;
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

		if($this->onGround or $this->hadCollision){
			$this->setCritical(false);
		}

		if($this->potionId != 0){
			if(!$this->onGround or ($this->onGround and ($tickDiff % 4) == 0)){
				$color = Potion::getColor($this->potionId - 1);
				$this->level->addParticle(new MobSpellParticle($this->add(
					$this->width / 2 + mt_rand(-100, 100) / 500,
					$this->height / 2 + mt_rand(-100, 100) / 500,
					$this->width / 2 + mt_rand(-100, 100) / 500), $color[0], $color[1], $color[2]));
			}
			$hasUpdate = true;
		}

		if($this->age > 1200){
			$this->close();
			$hasUpdate = true;
		}

		return $hasUpdate;
	}

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = Arrow::NETWORK_ID;
		$pk->eid = $this->getId();
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