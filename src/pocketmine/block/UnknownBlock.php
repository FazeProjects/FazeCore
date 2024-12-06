<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class UnknownBlock extends Transparent{
	public function isSolid(){
		return false;
	}

	public function getHardness(){
		return 0;
	}

    public function getDrops(Item $item) : array{
        return [];
    }
}