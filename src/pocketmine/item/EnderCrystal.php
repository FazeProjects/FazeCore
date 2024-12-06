<?php



declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\{Player, Server};
use pocketmine\nbt\tag\{CompoundTag, StringTag, ShortTag, ListTag, LongTag, ByteTag, IntTag, DoubleTag, FloatTag};
use pocketmine\entity\Entity;
use pocketmine\entity\EnderCrystal as FullCrystal;

class EnderCrystal extends Item{

	private $temporalVector = null;
	/**
	 * EyeOfEnder constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
    public function __construct($meta = 0, $count = 1){
        parent::__construct(Item::END_CRYSTAL, $meta, $count, "Ender Crystal");
    }

	public function canBeActivated() : bool{
		return true;
	}

	# Реализация ванильного спавна кристалла
	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
	    # Проверка на блок обсидиана
        if($target->getId() !== 49) return;
        if($level->getBlock($target->asVector3()->add(0, 1, 0))->getId() !== 0) return;

        $player->getInventory()->setItemInHand(Item::get(426, 0, $player->getItemInHand()->getCount() - 1)); # Проверяем и отнимаем кристалл

        $nbt = new CompoundTag('', [

            new ListTag('Pos', [new DoubleTag('', $target->getX() + 0.5), new DoubleTag('', $target->getY() + 1), new DoubleTag('', $target->getZ() + 0.5)]),

            new ListTag('Motion', [new DoubleTag('', 0.0), new DoubleTag('', 0.0), new DoubleTag('', 0.0)]),

            new ListTag('Rotation', [new FloatTag('', $player->getYaw()), new FloatTag('', $player->getPitch())])]);

            $npc = new FullCrystal($player->level, $nbt);
            $npc->spawnToAll();

	}
}