<?php



namespace pocketmine\entity\monster\walking;

use pocketmine\entity\Entity;
use pocketmine\entity\monster\WalkingMonster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\IronSword;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

class WitherSkeleton extends WalkingMonster {
	const NETWORK_ID = 48;

	private $angry = 0;

	public $width = 0.72;
	public $height = 1.8;
	public $eyeHeight = 1.62;
	protected $speed = 1;
	protected $lastdamager = null;
	private $attackTicks = 0;
	public $gamemode;

	public function getSpeed(){
		return $this->speed;
	}
	
	public function setSpeed($val){
		$this->speed = $val;
	}
	
	/**
	 * @return string
	 */
	public function getName(){
		return "Wither Skeleton";
	}

	public function initEntity(){
		parent::initEntity();
		$this->setFriendly(true);
		$this->fireProof = true;
		$this->setDamage([0, 5, 9, 13]);
	}

	public function saveNBT(){
		parent::saveNBT();
	}
	
	public function isAngry(){
		return $this->angry > 0;
	}

	public function setAngry($val, $damager = null){
		$this->angry = $val;
		$this->lastdamager = $damager;
		$this->setSpeed(2);
	}

	public function attack($damage, EntityDamageEvent $source){
		if(!$source->isCancelled()){
			if($source->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
				if(($player = $source->getDamager()) instanceof Player){
					if(!$player->isCreative() and !$player->isSpectator()){
						$this->setAngry(1000, $player);
					}
				}
			}
		}
		parent::attack($damage, $source);
	}
	
	public function spawnTo(Player $player){
		parent::spawnTo($player);

		$pk = new MobEquipmentPacket();
		$pk->eid = $this->getId();
		$pk->item = new IronSword();
		$pk->slot = 10;
		$pk->selectedSlot = 10;
		$player->dataPacket($pk);
	}

	public function attackEntity(Entity $player){
		if($this->distanceSquared($player) < 1.5){
			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
			$player->attack($ev->getFinalDamage(), $ev);
			if($player->getHealth() <= 0){
				$this->setAngry(0);
			}
		}
	}
	
	public function entityBaseTick($tickDiff = 1, $EnchantL = 0){

		//Timings::$timerEntityBaseTick->startTiming();

		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->isAngry()){
			$this->angry--;
		}else{
			$this->setSpeed(1);
		}
		//Timings::$timerEntityBaseTick->startTiming();
		return $hasUpdate;
	}

	public function getDrops(){
		$cause = $this->lastDamageCause;
		$drops = [];
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$lootingL = $damager->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
				if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
					$drops[] = ItemItem::get(ItemItem::SKULL, 1, 1);
				}
				$drops[] = ItemItem::get(ItemItem::BONE, 0, mt_rand(0, 2));
				$drops[] = ItemItem::get(ItemItem::COAL, 0, mt_rand(0, 1));
			}elseif($damager instanceof Creeper){
				if(($damager->isPowered()) and ($cause->getCause() == 10)){
					$drops[] = ItemItem::get(ItemItem::SKULL, 1, 1);
				}else{
					$drops[] = ItemItem::get(ItemItem::BONE, 0, mt_rand(0, 2));
					$drops[] = ItemItem::get(ItemItem::COAL, 0, mt_rand(0, 1));
				}
			}
		}

		return $drops;
	}
}