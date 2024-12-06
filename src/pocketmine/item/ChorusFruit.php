<?php



declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\item\Item;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\block\Liquid;
use pocketmine\entity\Living;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\math\Vector3;
use pocketmine\Player;

class ChorusFruit extends Item{

    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::CHORUS_FRUIT, $meta, $count, 'Chorus Fruit');
    }

    public function getFoodRestore() : int{
        return 4;
    }

    public function getSaturationRestore() : float{
        return 2.4;
    }

    public function requiresHunger() : bool{
        return true;
    }

    public function canBeConsumed() : bool{
        return true;
    }
    # код хоруса
    public function onConsume(Entity $consumer){
        $level = $consumer->getLevel();
        assert($level !== null);
        $consumer->getInventory()->setItemInHand(Item::get(432, 0, $consumer->getItemInHand()->getCount() - 1)); # Удаление одного хоруса когда сьешь!

        $minX = $consumer->getFloorX() - 8;
        $minY = min($consumer->getFloorY(), $consumer->getLevel()->getWorldHeight()) - 8;
        $minZ = $consumer->getFloorZ() - 8;

        $maxX = $minX + 16;
        $maxY = $minY + 16;
        $maxZ = $minZ + 16;

        for($attempts = 0; $attempts < 16; ++$attempts){
            $x = mt_rand($minX, $maxX);
            $y = mt_rand($minY, $maxY);
            $z = mt_rand($minZ, $maxZ);

            while($y >= 0 and !$level->getBlockAt($x, $y, $z)->isSolid()){
                --$y;
            }
            if($y < 0) continue;

            $blockUp = $level->getBlockAt($x, $y + 1, $z);
            $blockUp2 = $level->getBlockAt($x, $y + 2, $z);
            if($blockUp->isSolid() or $blockUp instanceof Liquid or $blockUp2->isSolid() or $blockUp2 instanceof Liquid) continue;

            $level->addSound(new EndermanTeleportSound($consumer->asVector3()));
            $consumer->teleport(new Vector3($x + 0.5, $y + 1, $z + 0.5));
            $level->addSound(new EndermanTeleportSound($consumer->asVector3()));

            break;
        }
    }
}