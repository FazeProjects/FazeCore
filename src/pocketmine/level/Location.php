<?php



namespace pocketmine\level;

use pocketmine\math\Vector3;

class Location extends Position {

	public $yaw;
	public $pitch;

	/**
	 * @param int   $x
	 * @param int   $y
	 * @param int   $z
	 * @param float $yaw
	 * @param float $pitch
	 * @param Level $level
	 */
	public function __construct($x = 0, $y = 0, $z = 0, $yaw = 0.0, $pitch = 0.0, Level $level = null){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
		$this->level = $level;
	}

	/**
	 * @param Vector3    $pos
	 * @param Level|null $level default null
	 * @param float      $yaw   default 0.0
	 * @param float      $pitch default 0.0
	 *
	 * @return Location
	 */
	public static function fromObject(Vector3 $pos, Level $level = null, $yaw = 0.0, $pitch = 0.0){
		return new Location($pos->x, $pos->y, $pos->z, $yaw, $pitch, ($level === null) ? (($pos instanceof Position) ? $pos->level : null) : $level);
	}

	/**
	 * Return a Location instance
	 * 
	 * @return Location
	 */
	public function asLocation() : Location{
		return new Location($this->x, $this->y, $this->z, $this->yaw, $this->pitch, $this->level);
	}

	/**
	 * @param     $x
	 * @param int $y
	 * @param int $z
	 * @param int $yaw
	 * @param int $pitch
	 *
	 * @return Location
	 */
	public function add($x, $y = 0, $z = 0, $yaw = 0, $pitch = 0){
		if($x instanceof Location){
			return new Location($this->x + $x->x, $this->y + $x->y, $this->z + $x->z, $this->yaw + $x->yaw, $this->pitch + $x->pitch, $this->level);
		}else{
			return new Location($this->x + $x, $this->y + $y, $this->z + $z, $this->yaw + $yaw, $this->pitch + $pitch, $this->level);
		}
	}

	/**
	 * @return float
	 */
	public function getYaw(){
		return $this->yaw;
	}

	/**
	 * @return float
	 */
	public function getPitch(){
		return $this->pitch;
	}

	/**
	 * @param Vector3 $pos
	 * @param         $x
	 * @param         $y
	 * @param         $z
	 *
	 * @return $this
	 */
	public function fromObjectAdd(Vector3 $pos, $x, $y, $z){
		if($pos instanceof Location){
			$this->yaw = $pos->yaw;
			$this->pitch = $pos->pitch;
		}
		parent::fromObjectAdd($pos, $x, $y, $z);
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return "Location (level=" . ($this->isValid() ? $this->getLevel()->getName() : "null") . ", x=$this->x, y=$this->y, z=$this->z, yaw=$this->yaw, pitch=$this->pitch)";
	}

	public function equals(Vector3 $v) : bool{
		if($v instanceof Location){
			return parent::equals($v) and $v->yaw == $this->yaw and $v->pitch == $this->pitch;
		}
		return parent::equals($v);
	}
}
