<?php



namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityCombustEvent extends EntityEvent implements Cancellable {
	public static $handlerList = null;

	protected $duration;
	protected $ProtectLevel;

	/**
	 * @param Entity $combustee
	 * @param int    $duration
	 * @param int    $ProtectLevel
	 */
	public function __construct(Entity $combustee, $duration, $ProtectLevel = 0){
		$this->entity = $combustee;
		$this->duration = $duration;
		$this->ProtectLevel = $ProtectLevel;
	}

	/**
	 * @return float|int
	 */
	public function getDuration(){
		if($this->ProtectLevel !== 0){
			return round($this->duration * (1 - 0.15 * $this->ProtectLevel));
		}else{
			return $this->duration;
		}
	}

	/**
	 * @param $duration
	 */
	public function setDuration($duration){
		$this->duration = (int) $duration;
	}

	/**
	 * @param $ProtectLevel
	 */
	public function setProtectLevel($ProtectLevel){
		$this->ProtectLevel = (int) $ProtectLevel;
	}
}