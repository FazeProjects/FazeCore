<?php



namespace pocketmine\block;

use pocketmine\entity\Effect;
use pocketmine\event\entity\EntityEatBlockEvent;
use pocketmine\item\FoodSource;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class Cake extends Transparent implements FoodSource {

	protected $id = self::CAKE_BLOCK;

	/**
	 * Cake constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.5;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Cake Block";
	}

	/**
	 * @return AxisAlignedBB
	 */
	protected function recalculateBoundingBox(){

		$f = $this->getDamage() * 0.125; //1 slice width

		return new AxisAlignedBB(
			$this->x + 0.0625 + $f,
			$this->y,
			$this->z + 0.0625,
			$this->x + 1 - 0.0625,
			$this->y + 0.5,
			$this->z + 1 - 0.0625
		);
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() !== self::AIR){
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	/**
	 * @param int $type
	 *
	 * @return bool|int
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getId() === self::AIR){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
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
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		//TODO: refactor this into generic food handling
		if($player instanceof Player and $player->getFood() < $player->getMaxFood()){
			$player->getServer()->getPluginManager()->callEvent($ev = new EntityEatBlockEvent($player, $this));

			if(!$ev->isCancelled()){
				$player->addFood($ev->getFoodRestore());
				$player->addSaturation($ev->getSaturationRestore());
				foreach($ev->getAdditionalEffects() as $effect){
					$player->addEffect($effect);
				}
				
				$this->getLevel()->setBlock($this, $ev->getResidue());
				return true;
			}
		}

		return false;
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 2;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 0.4;
	}

	/**
	 * @return Air|Cake
	 */
	public function getResidue(){
		$clone = clone $this;
		$clone->meta++;
		if($clone->meta > 0x06){
			$clone = new Air();
		}
		return $clone;
	}

	/**
	 * @return Effect[]
	 */
	public function getAdditionalEffects() : array{
		return [];
	}
}
