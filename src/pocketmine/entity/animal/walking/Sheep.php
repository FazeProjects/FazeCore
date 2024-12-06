<?php

namespace pocketmine\entity\animal\walking;

use pocketmine\entity\animal\WalkingAnimal;
use pocketmine\entity\Colorable;
use pocketmine\item\Item as ItemItem;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Creature;

class Sheep extends WalkingAnimal implements Colorable{

	const NETWORK_ID = 13;

	public $width = 1.45;
	public $height = 1.12;

	public function getName() : string{
		return "Sheep";
	}

	public function initEntity(){
		parent::initEntity();

		$this->setMaxHealth(8);
	}

	public function targetOption(Creature $creature, $distance){
		if($creature instanceof Player){
			return $creature->spawned && $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == Item::WHEAT && $distance <= 39;
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
					$drops[] = ItemItem::get(ItemItem::WOOL, 0, $count);
				}
				$count = mt_rand(1, 2 + $lootingL);
				if($count > 0){
					$drops[] = ItemItem::get(ItemItem::RAW_MUTTON, 0, $count);
				}
			}
		}
		return $drops;
	}

}
