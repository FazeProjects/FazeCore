<?php

namespace pocketmine\entity\feature;

use pocketmine\item\Item as ItemItem;
use pocketmine\Player;

interface Interactive {

	public function onInteract(Player $player, ItemItem $item): bool;

}