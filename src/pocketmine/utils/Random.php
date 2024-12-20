<?php



namespace pocketmine\utils;

/**
 * XorShift128Engine Random Number Noise, used for fast seeded values
 * Most of the code in this class was adapted from the XorShift128Engine in the php-random library.
 */
class Random {
	const X = 123456789;
	const Y = 362436069;
	const Z = 521288629;
	const W = 88675123;

	/**
	 * @var int
	 */
	private $x;

	/**
	 * @var int
	 */
	private $y;

	/**
	 * @var int
	 */
	private $z;

	/**
	 * @var int
	 */
	private $w;

	protected $seed;

	/**
	 * @param int $seed Integer to be used as seed.
	 */
	public function __construct($seed = -1){
		if($seed === -1){
			$seed = time();
		}

		$this->setSeed($seed);
	}

	/**
	 * @param int $seed Integer to be used as seed.
	 */
	public function setSeed($seed){
		$this->seed = $seed;
		$this->x = self::X ^ $seed;
		$this->y = self::Y ^ ($seed << 17) | (($seed >> 15) & 0x7fffffff) & 0xffffffff;
		$this->z = self::Z ^ ($seed << 31) | (($seed >> 1) & 0x7fffffff) & 0xffffffff;
		$this->w = self::W ^ ($seed << 18) | (($seed >> 14) & 0x7fffffff) & 0xffffffff;
	}

	public function getSeed(){
		return $this->seed;
	}

	/**
	 * Returns an 31-bit integer (not signed)
	 *
	 * @return int
	 */
	public function nextInt(){
		return $this->nextSignedInt() & 0x7fffffff;
	}

	/**
	 * Returns a 32-bit integer (signed)
	 *
	 * @return int
	 */
	public function nextSignedInt(){
		$t = ($this->x ^ ($this->x << 11)) & 0xffffffff;

		$this->x = $this->y;
		$this->y = $this->z;
		$this->z = $this->w;
		$this->w = ($this->w ^ (($this->w >> 19) & 0x7fffffff) ^ ($t ^ (($t >> 8) & 0x7fffffff))) & 0xffffffff;

		return $this->w;
	}

	/**
	 * Returns a float between 0.0 and 1.0 (inclusive)
	 *
	 * @return float
	 */
	public function nextFloat(){
		return $this->nextInt() / 0x7fffffff;
	}

	/**
	 * Returns a float between -1.0 and 1.0 (inclusive)
	 *
	 * @return float
	 */
	public function nextSignedFloat(){
		return $this->nextSignedInt() / 0x7fffffff;
	}

	/**
	 * Returns a random boolean
	 *
	 * @return bool
	 */
	public function nextBoolean(){
		return ($this->nextSignedInt() & 0x01) === 0;
	}

	/**
	 * Returns a random integer between $start and $end
	 *
	 * @param int $start default 0
	 * @param int $end   default 0x7fffffff
	 *
	 * @return int
	 */
	public function nextRange($start = 0, $end = 0x7fffffff){
		return $start + ($this->nextInt() % ($end + 1 - $start));
	}

	/**
	 * @param $bound
	 *
	 * @return int
	 */
	public function nextBoundedInt($bound){
		return $this->nextInt() % $bound;
	}

}