<?php



namespace pocketmine\inventory;

use pocketmine\math\Vector3;

class WindowHolder extends Vector3 implements InventoryHolder{
    protected $inventory;

    public function __construct(int $x, int $y, int $z, Inventory $inventory){
        parent::__construct($x, $y, $z);
        $this->inventory = $inventory;
    }

    public function getInventory(): Inventory{
        return $this->inventory;
    }
}