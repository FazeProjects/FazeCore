<?php



namespace pocketmine\utils;

class Range {
	public $minValue;
	public $maxValue;

	/**
	 * Range constructor.
	 *
	 * @param int $min
	 * @param int $max
	 */
	public function __construct(int $min, int $max){
		$this->minValue = $min;
		$this->maxValue = $max;
	}

	/**
	 * @param int $v
	 *
	 * @return bool
	 */
	public function isInRange(int $v) : bool{
		return $v >= $this->minValue && $v <= $this->maxValue;
	}
}