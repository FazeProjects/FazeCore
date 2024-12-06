<?php

namespace pocketmine\entity\animal\walking;

use pocketmine\entity\animal\WalkingAnimal;
use pocketmine\item\Item as ItemItem;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Creature;

class Cow extends WalkingAnimal{

	const NETWORK_ID = 11;

	public $width = 1.45;
	public $height = 1.12;

	public function getName() : string{
		return "Cow";
	}

	public function initEntity(){
		parent::initEntity();

		$this->setMaxHealth(10);
	}

	public function targetOption(Creature $creature, $distance){
		if($creature instanceof Player){
			return $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == ItemItem::WHEAT && $distance <= 39;
		}
		return false;
	}

	public function getDrops(){
		$cause = $this->lastDamageCause;
		$drops = [];
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$lootingL = $damager->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
				$count = mt_rand(1, 2 + $lootingL);
				if($count > 0){
					$drops[] = ItemItem::get(ItemItem::GOLDEN_APPLE, 0, $count);
				}
			}
		}
		return $drops;
	}
}
