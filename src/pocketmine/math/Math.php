<?php



namespace pocketmine\math;

use function sqrt;
use function max;
use function min;

abstract class Math{

	/**
	 * @param float $n
	 */
	public static function floorFloat($n) : int{
		$i = (int) $n;
		return $n >= $i ? $i : $i - 1;
	}

	/**
	 * @param float $n
	 */
	public static function ceilFloat($n) : int{
		$i = (int) $n;
		return $n <= $i ? $i : $i + 1;
	}

	/**
	 * @param $value
	 * @param $low
	 * @param $high
	 *
	 * @return mixed
	 */
	public static function clamp($value, $low, $high){
		return min($high, max($low, $value));
	}

	/**
	 * Solves a quadratic equation with the given coefficients and returns an array of up to two solutions.
	 *
	 * @return float[]
	 */
	public static function solveQuadratic(float $a, float $b, float $c) : array{
		$discriminant = $b ** 2 - 4 * $a * $c;
		if($discriminant > 0){ //2 real roots
			$sqrtDiscriminant = sqrt($discriminant);
			return [
				(-$b + $sqrtDiscriminant) / (2 * $a),
				(-$b - $sqrtDiscriminant) / (2 * $a)
			];
		}elseif($discriminant == 0){ //1 real root
			return [
				-$b / (2 * $a)
			];
		}else{ //No real roots
			return [];
		}
	}
}