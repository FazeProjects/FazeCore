<?php



namespace pocketmine\math;

use function cos;
use function sin;

abstract class VectorMath{

	public static function getDirection2D(float $azimuth) : Vector2{
		return new Vector2(cos($azimuth), sin($azimuth));
	}

	/**
	 * @param $azimuth
	 * @param $inclination
	 *
	 * @return Vector3
	 */
	public static function getDirection3D($azimuth, $inclination) : Vector3{
		$yFact = cos($inclination);
		return new Vector3($yFact * cos($azimuth), sin($inclination), $yFact * sin($azimuth));
	}

}