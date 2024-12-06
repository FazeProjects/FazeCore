<?php



declare(strict_types=1);

namespace pocketmine\block;

class StickyPiston extends Piston {
	
	protected $id = self::STICKY_PISTON;
	
	public $meta = 0;
	
	public function __construct($meta = 0){
		$this->meta = $meta;
	}
}
