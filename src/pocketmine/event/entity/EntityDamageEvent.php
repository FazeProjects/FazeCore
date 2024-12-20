<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\inventory\PlayerInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Called when the entity takes damage.
 */
class EntityDamageEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	const MODIFIER_BASE = 0;
	const MODIFIER_RESISTANCE = 1;
	const MODIFIER_ARMOR = 2;
	const MODIFIER_PROTECTION = 3;
	const MODIFIER_STRENGTH = 4;
	const MODIFIER_WEAKNESS = 5;
	const MODIFIER_CRITICAL = 7;
	const MODIFIER_TOTEM = 8;

	const CAUSE_CONTACT = 0;
	const CAUSE_ENTITY_ATTACK = 1;
	const CAUSE_PROJECTILE = 2;
	const CAUSE_SUFFOCATION = 3;
	const CAUSE_FALL = 4;
	const CAUSE_FIRE = 5;
	const CAUSE_FIRE_TICK = 6;
	const CAUSE_LAVA = 7;
	const CAUSE_DROWNING = 8;
	const CAUSE_BLOCK_EXPLOSION = 9;
	const CAUSE_ENTITY_EXPLOSION = 10;
	const CAUSE_VOID = 11;
	const CAUSE_SUICIDE = 12;
	const CAUSE_MAGIC = 13;
	const CAUSE_CUSTOM = 14;
	const CAUSE_STARVATION = 15;
	const CAUSE_LIGHTNING = 16;

	private $cause;
	private $EPF = 0;
	private $fireProtectL = 0;
	/** @var array */
	private $modifiers;
	private $rateModifiers = [];
	private $originals;
	private $usedArmors = [];
	private $thornsLevel = [];
	private $thornsArmor;
	private $thornsDamage = 0;


	/**
	 * @param Entity        $entity
	 * @param int           $cause
	 * @param float|float[] $damage
	 */
	public function __construct(Entity $entity, int $cause, $damage){
		$this->entity = $entity;
		$this->cause = $cause;
		if(is_array($damage)){
			$this->modifiers = $damage;
		}else{
			$this->modifiers = [
				self::MODIFIER_BASE => $damage
			];
		}

		$this->originals = $this->modifiers;

		if(!isset($this->modifiers[self::MODIFIER_BASE])){
            throw new \InvalidArgumentException("Missing BASE damage modifier");
		}

		//For DAMAGE_RESISTANCE
		if($cause !== self::CAUSE_VOID and $cause !== self::CAUSE_SUICIDE){
			if($entity->hasEffect(Effect::DAMAGE_RESISTANCE)){
				$RES_level = 1 - 0.20 * ($entity->getEffect(Effect::DAMAGE_RESISTANCE)->getEffectLevel());
				if($RES_level < 0){
					$RES_level = 0;
				}
				$this->setRateDamage($RES_level, self::MODIFIER_RESISTANCE);
			}
		}

		//TODO: add zombie
		if($entity instanceof Player and $entity->getInventory() instanceof PlayerInventory){
			switch($cause){
				case self::CAUSE_CONTACT:
				case self::CAUSE_ENTITY_ATTACK:
				case self::CAUSE_PROJECTILE:
				case self::CAUSE_FIRE:
				case self::CAUSE_LAVA:
				case self::CAUSE_BLOCK_EXPLOSION:
				case self::CAUSE_ENTITY_EXPLOSION:
				case self::CAUSE_LIGHTNING:
					$points = 0;
					foreach($entity->getInventory()->getArmorContents() as $index => $i){
						if($i->isArmor()){
							$points += $i->getArmorValue();
							$this->usedArmors[$index] = 1;
						}
					}
					if($points !== 0){
						$this->setRateDamage(1 - 0.04 * $points, self::MODIFIER_ARMOR);
					}
					//For Protection
					$spe_Prote = null;
					switch($cause){
						case self::CAUSE_ENTITY_EXPLOSION:
						case self::CAUSE_BLOCK_EXPLOSION:
							$spe_Prote = Enchantment::TYPE_ARMOR_EXPLOSION_PROTECTION;
							break;
						case self::CAUSE_FIRE:
						case self::CAUSE_LAVA:
							$spe_Prote = Enchantment::TYPE_ARMOR_FIRE_PROTECTION;
							break;
						case self::CAUSE_PROJECTILE:
							$spe_Prote = Enchantment::TYPE_ARMOR_PROJECTILE_PROTECTION;
							break;
						default;
							break;
					}
					foreach($this->usedArmors as $index => $cost){
						$i = $entity->getInventory()->getArmorItem($index);
						if($i->isArmor()){
							$this->EPF += $i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_PROTECTION);
							$this->fireProtectL = max($this->fireProtectL, $i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_FIRE_PROTECTION));
							if($i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_THORNS) > 0){
								$this->thornsLevel[$index] = $i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_THORNS);
							}
							if($spe_Prote !== null){
								$this->EPF += 2 * $i->getEnchantmentLevel($spe_Prote);
							}
						}
					}
					break;
				case self::CAUSE_FALL:
					//Feather Falling
					$i = $entity->getInventory()->getBoots();
					if($i->isArmor()){
						$this->EPF += $i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_PROTECTION);
						$this->EPF += 3 * $i->getEnchantmentLevel(Enchantment::TYPE_ARMOR_FALL_PROTECTION);
					}
					break;
				case self::CAUSE_FIRE_TICK:
				case self::CAUSE_SUFFOCATION:
				case self::CAUSE_DROWNING:
				case self::CAUSE_VOID:
				case self::CAUSE_SUICIDE:
				case self::CAUSE_MAGIC:
                case self::CAUSE_CUSTOM:
					break;
                case self::CAUSE_STARVATION:
                default:
					break;
			}
			if($this->EPF !== 0){
				$this->EPF = min(20, ceil($this->EPF * mt_rand(50, 100) / 100));
				$this->setRateDamage(1 - 0.04 * $this->EPF, self::MODIFIER_PROTECTION);
			}
		}
	}

	/**
	 * @return int
	 */
	public function getCause() : int{
		return $this->cause;
	}

	/**
	 * @param int $type
	 *
	 * @return float
	 */
	public function getOriginalDamage(int $type = self::MODIFIER_BASE) : float{
		if(isset($this->originals[$type])){
			return $this->originals[$type];
		}
		return 0.0;
	}

	/**
	 * @param int $type
	 *
	 * @return float
	 */
	public function getDamage(int $type = self::MODIFIER_BASE) : float{
		if(isset($this->modifiers[$type])){
			return $this->modifiers[$type];
		}

		return 0.0;
	}

	/**
	 * @param float $damage
	 * @param int   $type
	 */
	public function setDamage(float $damage, int $type = self::MODIFIER_BASE){
		$this->modifiers[$type] = $damage;
	}

	/**
	 * @param int $type
	 *
	 * @return float 1 - the percentage
	 */
	public function getRateDamage($type = self::MODIFIER_BASE){
		if(isset($this->rateModifiers[$type])){
			return $this->rateModifiers[$type];
		}
		return 1;
	}

    /**
     * @param float $damage
     * @param int $type
     *
     * Note: If you want to add/reduce damage without armor reduction or effect. set new damage with setDamage
     * Note: If you want to add/reduce damage within armor reduction effect. Please change MODIFIER_BASE
     * Note: If you want to add/reduce damage by multiplication. Please use this function.
     */
	public function setRateDamage($damage, $type = self::MODIFIER_BASE){
		$this->rateModifiers[$type] = $damage;
	}

	/**
	 * @param int $type
	 *
	 * @return bool
	 */
	public function isApplicable(int $type){
		return isset($this->modifiers[$type]);
	}

	/**
	 * @return float
	 */
	public function getFinalDamage(){
		$damage = $this->modifiers[self::MODIFIER_BASE];
		foreach($this->rateModifiers as $type => $d){
			$damage *= $d;
		}
		foreach($this->modifiers as $type => $d){
			if($type !== self::MODIFIER_BASE){
				$damage += $d;
			}
		}
		return $damage;
	}

	/**
	 * @return Item $usedArmors
	 * notice: $usedArmors $index->$cost
	 * $index: the $index of ArmorInventory
	 * $cost:  the num of durability cost
	 */
	public function getUsedArmors(){
		return $this->usedArmors;
	}

	/**
	 * @return Int $fireProtectL
	 */
	public function getFireProtectL(){
		return $this->fireProtectL;
	}

	/**
	 * @return bool
	 */
	public function useArmors(){
		if($this->entity instanceof Player){
			if($this->entity->isSurvival() and $this->entity->isAlive()){
				foreach($this->usedArmors as $index => $cost){
					$i = $this->entity->getInventory()->getArmorItem($index);
					if($i->isArmor()){
						$this->entity->getInventory()->damageArmor($index, $cost);
					}
				}
			}
			return true;
		}
		return false;
	}

	public function createThornsDamage(){
		if($this->thornsLevel !== []){
			$this->thornsArmor = array_rand($this->thornsLevel);
			$thornsL = $this->thornsLevel[$this->thornsArmor];
			if(mt_rand(1, 100) < $thornsL * 15){
				//$this->thornsDamage = mt_rand(1, 4); 
				$this->thornsDamage = 0; //Delete When #321 Is Fixed And Add In The Normal Damage
			}
		}
	}

	/**
	 * @return int
	 */
	public function getThornsDamage(){
		return $this->thornsDamage;
	}

	/**
	 * @return bool should be used after getThornsDamage()
	 */
	public function setThornsArmorUse(){
		if($this->thornsArmor === null){
			return false;
		}else{
			$this->usedArmors[$this->thornsArmor] = 3;
			return true;
		}
	}
}
