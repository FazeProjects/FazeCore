<?php



namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Cobweb extends Flowable {

	protected $id = self::COBWEB;

	/**
	 * Cobweb constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
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
		return "Cobweb";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 4;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}

	/**
	 * @param Entity $entity
	 */
	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isShears()){
			return [
				[Item::COBWEB, 0, 1],
			];
		}elseif($item->isSword() >= Tool::TIER_WOODEN){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::COBWEB, 0, 1],
				];
			}else{
				return [
					[Item::STRING, 0, 1],
				];
			}
		}
		return [];
	}

	public function diffusesSkyLight() : bool{
		return true;
	}
}