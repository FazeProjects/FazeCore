<?php
namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class WitherTNT extends Entity implements Explosive {
	const NETWORK_ID = 65;

	public $width = 0.98;
	public $length = 0.98;
	public $height = 0.98;

	protected $gravity = 0.04;
	protected $drag = 0.02;

	protected $fuse;

	public $canCollide = false;

	private $dropItem = true;

	/**
	 * PrimedTNT constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 * @param bool        $dropItem
	 */
	public function __construct(Level $level, CompoundTag $nbt, bool $dropItem = true){
		parent::__construct($level, $nbt);
		$this->dropItem = $dropItem;
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

		if(isset($this->namedtag->Fuse)){
			$this->fuse = $this->namedtag["Fuse"];
		}else{
			$this->fuse = 120;
		}

		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_IGNITED, true);
		$this->setDataProperty(self::DATA_FUSE_LENGTH, self::DATA_TYPE_INT, $this->fuse);
	}


	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canCollideWith(Entity $entity){
		return false;
	}

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Fuse = new ByteTag("Fuse", $this->fuse);
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
			if($this->onGround){
				if($this->fuse > 10){
					$this->fuse = 10;
				}
			}

			if($this->fuse <= 0){
				$this->kill();
				$this->explode();
			}else{
				$this->fuse--;
			}
		}

		return $hasUpdate or $this->fuse >= 0;
	}

	public function explode(){
		$this->server->getPluginManager()->callEvent($ev = new ExplosionPrimeEvent($this, 3, $this->dropItem));

		if(!$ev->isCancelled()){
			$explosion = new Explosion($this, $ev->getForce(), $this, $ev->dropItem(), null);
			if($ev->isBlockBreaking()){
				$explosion->explodeC();
			}
			$explosion->explodeB();
		}
	}

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = WitherTNT::NETWORK_ID;
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