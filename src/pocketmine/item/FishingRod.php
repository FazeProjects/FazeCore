<?php



namespace pocketmine\item;

class FishingRod extends Tool{

    /**
	 * FishingRod constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FISHING_ROD, $meta, $count, "Fishing Rod");
	}

    public function getMaxDurability(){
        return 65;
    }
} 
