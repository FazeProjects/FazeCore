<?php



namespace pocketmine\block;

use pocketmine\entity\Arrow;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockBurnEvent;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Server;

class Fire extends Flowable {

	protected $id = self::FIRE;

	/** @var Vector3 */
	private $temporalVector = null;

	/**
	 * Fire constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
		if($this->temporalVector === null){
			$this->temporalVector = new Vector3(0, 0, 0);
		}
	}

	/**
	 * @return bool
	 */
	public function hasEntityCollision(){
		return true;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Fire Block";
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 15;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced(){
		return true;
	}

	/**
	 * @param Entity $entity
	 */
	public function onEntityCollide(Entity $entity){
		$ProtectL = 0;
		if(!$entity->hasEffect(Effect::FIRE_RESISTANCE)){
			$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_FIRE, 1);
			if($entity->attack($ev->getFinalDamage(), $ev) === true){
				$ev->useArmors();
			}
			$ProtectL = $ev->getFireProtectL();
		}

		$ev = new EntityCombustByBlockEvent($this, $entity, 8, $ProtectL);
		if($entity instanceof Arrow){
			$ev->setCancelled();
		}
		Server::getInstance()->getPluginManager()->callEvent($ev);
		if(!$ev->isCancelled()){
			$entity->setOnFire($ev->getDuration());
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [];
	}

	/**
	 * @param int $type
	 *
	 * @return int
	 */
	public function onUpdate($type){
		if($type == Level::BLOCK_UPDATE_NORMAL or $type == Level::BLOCK_UPDATE_RANDOM or $type == Level::BLOCK_UPDATE_SCHEDULED){
			if(!$this->getSide(Vector3::SIDE_DOWN)->isTopFacingSurfaceSolid() and !$this->canNeighborBurn()){
				$this->getLevel()->setBlock($this, new Air(), true);
				return Level::BLOCK_UPDATE_NORMAL;
			}elseif($type == Level::BLOCK_UPDATE_NORMAL or $type == Level::BLOCK_UPDATE_RANDOM){
				$this->getLevel()->scheduleUpdate($this, $this->getTickRate() + mt_rand(0, 10));
			}elseif($type == Level::BLOCK_UPDATE_SCHEDULED and $this->getLevel()->getServer()->fireSpread){
				$forever = $this->getSide(Vector3::SIDE_DOWN)->getId() == Block::NETHERRACK;

				//TODO: END

				if(!$this->getSide(Vector3::SIDE_DOWN)->isTopFacingSurfaceSolid() and !$this->canNeighborBurn()){
					$this->getLevel()->setBlock($this, new Air(), true);
				}

				if(!$forever and $this->getLevel()->getWeather()->isRainy() and
					($this->getLevel()->canBlockSeeSky($this) or
						$this->getLevel()->canBlockSeeSky($this->getSide(Vector3::SIDE_EAST)) or
						$this->getLevel()->canBlockSeeSky($this->getSide(Vector3::SIDE_WEST)) or
						$this->getLevel()->canBlockSeeSky($this->getSide(Vector3::SIDE_SOUTH)) or
						$this->getLevel()->canBlockSeeSky($this->getSide(Vector3::SIDE_NORTH))
					)
				){
					$this->getLevel()->setBlock($this, new Air(), true);
				}else{
					$meta = $this->meta;

					if($meta < 15 and mt_rand(0, 2) === 0){
						$this->meta++;
						$this->getLevel()->setBlock($this, $this, true);
					}

					$this->getLevel()->scheduleUpdate($this, $this->getTickRate() + mt_rand(0, 10));

					if(!$forever and !$this->canNeighborBurn()){
						if(!$this->getSide(Vector3::SIDE_DOWN)->isTopFacingSurfaceSolid() or $meta > 3){
							$this->getLevel()->setBlock($this, new Air(), true);
						}
					}elseif(!$forever && !($this->getSide(Vector3::SIDE_DOWN)->getBurnAbility() > 0) && $meta >= 15 && mt_rand(0, 4) == 0){
						$this->getLevel()->setBlock($this, new Air(), true);
					}else{
						$o = 0;

						//TODO: decrease the o if the rainfall values are high

						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_EAST), 300 + $o, $meta);
						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_WEST), 300 + $o, $meta);
						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_DOWN), 250 + $o, $meta);
						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_UP), 250 + $o, $meta);
						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_SOUTH), 300 + $o, $meta);
						$this->tryToCatchBlockOnFire($this->getSide(Vector3::SIDE_NORTH), 300 + $o, $meta);

						for($x = ($this->x - 1); $x <= ($this->x + 1); ++$x){
							for($z = ($this->z - 1); $z <= ($this->z + 1); ++$z){
								for($y = ($this->y - 1); $y <= ($this->y + 4); ++$y){
									$k = 100;

									if($y > $this->y + 1){
										$k += ($y - ($this->y + 1)) * 100;
									}

									$chance = $this->getChanceOfNeighborsEncouragingFire($this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $z)));

									if($chance > 0){
										$t = ($chance + 40 + $this->getLevel()->getServer()->getDifficulty() * 7);

										//TODO: decrease t if the rainfall values are high

										if($t > 0 and mt_rand(0, $k) <= $t){
											$damage = min(15, $meta + mt_rand(0, 5) / 4);

											$this->getLevel()->setBlock($this->temporalVector->setComponents($x, $y, $z), new Fire($damage), true);
											$this->getLevel()->scheduleUpdate($this->temporalVector, $this->getTickRate());
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return 0;
	}

	/**
	 * @return int
	 */
	public function getTickRate() : int{
		return 30;
	}

	/*public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			for($s = 0; $s <= 5; ++$s){
				$side = $this->getSide($s);
				if($side->getId() !== self::AIR and !($side instanceof Liquid)){
					return false;
				}
			}
			$this->getLevel()->setBlock($this, new Air(), true);

			return Level::BLOCK_UPDATE_NORMAL;
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->getSide(0)->getId() !== self::NETHERRACK){
				$this->getLevel()->setBlock($this, new Air(), true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}*/

	/**
	 * @param Block $block
	 * @param int   $bound
	 * @param int   $damage
	 */
	private function tryToCatchBlockOnFire(Block $block, int $bound, int $damage){
		$burnAbility = $block->getBurnAbility();

		if(mt_rand(0, $bound) < $burnAbility){
			if(mt_rand(0, $damage + 10) < 5){
				$meta = max(15, $damage + mt_rand(0, 4) / 4);

				$this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new BlockBurnEvent($block));
				if(!$ev->isCancelled()){
					$this->getLevel()->setBlock($block, $fire = new Fire($meta), true);
					$this->getLevel()->scheduleUpdate($block, $fire->getTickRate());
				}
			}else{
				$this->getLevel()->setBlock($this, new Air(), true);
			}

			if($block instanceof TNT){
				$block->prime();
			}
		}
	}

	/**
	 * @param Block $block
	 *
	 * @return int|mixed
	 */
	private function getChanceOfNeighborsEncouragingFire(Block $block){
		if($block->getId() !== self::AIR){
			return 0;
		}else{
			$chance = 0;
			for($i = 0; $i < 5; $i++){
				$chance = max($chance, $block->getSide($i)->getBurnChance());
			}
			return $chance;
		}
	}
}
