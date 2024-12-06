<?php



namespace pocketmine\entity;

use pocketmine\block\BlockIds;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\Player;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\math\Vector3;

class FishingHook extends Projectile {
	const NETWORK_ID = 77;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.06;
	protected $drag = 0.05;

    protected $shootingPlayer;

	public $data = 0;
	public $attractTimer = 100;
	public $coughtTimer = 0;

	public function initEntity(){
		parent::initEntity();
	}

	/**
	 * FishingHook constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 * @param Player|null $shootingPlayer
	 */
	public function __construct(Level $level, CompoundTag $nbt, Player $shootingPlayer = null) {
		parent::__construct($level, $nbt, $shootingPlayer);
        $this->shootingPlayer = $shootingPlayer;
	}

	/**
	 * @param $id
	 */
	public function setData($id): void {
		$this->data = $id;
	}

	/**
	 * @return int
	 */
	public function getData(): int {
		return $this->data;
	}

	/**
	 * @param $currentTick
	 *
	 * @return void
     */
	public function onUpdate($currentTick): bool {
		if($this->closed) return false;

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);
        if($this->isInsideOfWater()){
            $this->setMotion(new Vector3(0, 0.23, 0));
		    if($this->attractTimer === 0 && mt_rand(0, 100) <= 60){ // шанс, что рыба клюнет
			    $this->coughtTimer = mt_rand(5, 10) * 10; // случайная задержка, чтобы поймать рыбу
			    $this->attractTimer = 10 * 20; // сбросить таймер
			    $this->attractFish();
			    if(!$this->getOwningEntity() instanceof Player) return false;
			    if($this->shootingPlayer->getItemInHand()->getId() !== 346) return false;
			    $ihand = $this->shootingPlayer->getItemInHand();
			    if($ihand->getDamage() >= 380) return $this->shootingPlayer->getInventory()->setItemInHand(ItemItem::get(0, 0, 0));
			    if($ihand->hasEnchantment(23)){
			    	switch($ihand->getEnchantment(23)->getLevel()){
			    		case 1: 
			    			$fishes = [ItemIds::RAW_FISH, ItemIds::GLASS_BOTTLE, ItemIds::POISONOUS_POTATO];
			    		break;
			    		case 2: 
			    			$fishes = [ItemIds::RAW_FISH, ItemIds::RAW_SALMON, ItemIds::CLOWN_FISH, ItemIds::PUFFER_FISH, ItemIds::BONE, ItemIds::GLASS_BOTTLE];
			    		break;
			    		case 3: 
			    			$fishes = [ItemIds::RAW_FISH, ItemIds::RAW_SALMON, ItemIds::CLOWN_FISH, ItemIds::PUFFER_FISH, ItemIds::BONE, ItemIds::ROTTEN_FLESH, ItemIds::ARROW, ItemIds::SUGAR, ItemIds::GLASS_BOTTLE, ItemIds::POTION, ItemIds::NETHER_WART, BlockIds::SOUL_SAND];
			    		break;
                        default:
                            $fishes = [ItemIds::RAW_SALMON];
                        break;
			    	}
			    }else $fishes = [ItemIds::RAW_FISH];
			    $fish = array_rand($fishes);
			    if(mt_rand(1, 100) < 3){
			    	$item2 = ItemItem::get(346);
			    	$item2->addEnchantment(Enchantment::getEnchantment(23)->setLevel(mt_rand(1, 2)));
			    	$item2->setDamage(mt_rand(280, 340));
                    $this->shootingPlayer->getInventory()->addItem($item2);
			    }
			    if(mt_rand(1, 100) < 2){
			    	$item2 = ItemItem::get(mt_rand(310, 313));
			    	$item2->addEnchantment(Enchantment::getEnchantment(0)->setLevel(mt_rand(1, 4)));
			    	$item2->setDamage(mt_rand(200, 340));
                    $this->shootingPlayer->getInventory()->addItem($item2);
			    }
			    $item = ItemItem::get($fishes[$fish]);
			    $this->getLevel()->getServer()->getPluginManager()->callEvent($ev = new PlayerFishEvent($this->shootingPlayer, $item, $this));
			    if(!$ev->isCancelled()){
					$this->shootingPlayer->addXp(mt_rand(1, 4));
			    	$ihand->setDamage($ihand->getDamage() + 2);
                    $this->shootingPlayer->getInventory()->setItemInHand($ihand);
                    $this->shootingPlayer->getInventory()->addItem($item);
			    }
                $this->shootingPlayer->unlinkHookFromPlayer();
		    }elseif($this->attractTimer > 0) --$this->attractTimer;
		    if($this->coughtTimer > 0){
			    --$this->coughtTimer;
			    $this->fishBites();
		    }
		}
		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function fishBites(){
		if($this->getOwningEntity() instanceof Player){
			$pk = new EntityEventPacket();
			$pk->eid = $this->getOwningEntity()->getId();//$this or $p
			$pk->event = EntityEventPacket::FISH_HOOK_HOOK;
			$this->server->broadcastPacket($this->getOwningEntity()->hasSpawned, $pk);
		}
	}

	public function attractFish(){
		if($this->getOwningEntity() instanceof Player){
			$pk = new EntityEventPacket();
			$pk->eid = $this->getOwningEntity()->getId();//$this or $p
			$pk->event = EntityEventPacket::FISH_HOOK_BUBBLE;
			$this->server->broadcastPacket($this->getOwningEntity()->hasSpawned, $pk);
		}
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
