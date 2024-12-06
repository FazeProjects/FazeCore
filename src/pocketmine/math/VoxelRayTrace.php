<?php



namespace pocketmine\math;

use function floor;
use const INF;

abstract class VoxelRayTrace {

    /**
     * Performs ray tracing from the starting position in the given direction for a distance of $maxDistance. This
     * returns a generator yielding Vector3 instances that contain the coordinates of the voxels through which the ray passes.
     *
     * @return \Generator|Vector3[]
     * @phpstan-return \Generator<int, Vector3, void, void>
     */
    public static function inDirection(Vector3 $start, Vector3 $directionVector, float $maxDistance) : \Generator{
        return self::betweenPoints($start, $start->add($directionVector->multiply($maxDistance)));
    }

    /**
     * Performs ray tracing between the start and end coordinates. This returns a generator yielding Vector3 instances
     * that contain the coordinates of the voxels through which the ray passes.
     *
     * This is an implementation of the algorithm described in the link below.
     * @link http://www.cse.yorku.ca/~amana/research/grid.pdf
     *
     * @return \Generator|Vector3[]
     * @phpstan-return \Generator<int, Vector3, void, void>
     */
    public static function betweenPoints(Vector3 $start, Vector3 $end) : \Generator{
        $currentBlock = $start->floor();

        $directionVector = $end->subtract($start)->normalize();
        if($directionVector->lengthSquared() <= 0){
            throw new \InvalidArgumentException("The start and end points are the same, resulting in a zero direction vector.");
        }

        $radius = $start->distance($end);

        $stepX = $directionVector->x <=> 0;
        $stepY = $directionVector->y <=> 0;
        $stepZ = $directionVector->z <=> 0;

        // Initialize the step accumulation variables depending on how far the start position is into the current block.
        // If the start position is at the corner of a block, they will be zero.
        $tMaxX = self::rayTraceDistanceToBoundary($start->x, $directionVector->x);
        $tMaxY = self::rayTraceDistanceToBoundary($start->y, $directionVector->y);
        $tMaxZ = self::rayTraceDistanceToBoundary($start->z, $directionVector->z);

        // The change in t on each axis when taking a step on that axis (always positive).
        $tDeltaX = $directionVector->x == 0 ? 0 : $stepX / $directionVector->x;
        $tDeltaY = $directionVector->y == 0 ? 0 : $stepY / $directionVector->y;
        $tDeltaZ = $directionVector->z == 0 ? 0 : $stepZ / $directionVector->z;

        while(true){
            yield $currentBlock;

            // tMaxX stores the value of t when we cross the cube boundary along the X axis,
            // similarly for Y and Z. Hence, the smallest tMax value selects the nearest cube boundary.
            if($tMaxX < $tMaxY and $tMaxX < $tMaxZ){
                if($tMaxX > $radius){
                    break;
                }
                $currentBlock->x += $stepX;
                $tMaxX += $tDeltaX;
            }elseif($tMaxY < $tMaxZ){
                if($tMaxY > $radius){
                    break;
                }
                $currentBlock->y += $stepY;
                $tMaxY += $tDeltaY;
            }else{
                if($tMaxZ > $radius){
                    break;
                }
                $currentBlock->z += $stepZ;
                $tMaxZ += $tDeltaZ;
            }
        }
    }

    /**
     * Returns the distance that needs to be traveled along an axis from the starting point with the direction vector's component
     * to cross the block boundary.
     *
     * For example, given the X coordinate inside a block and the X component of the direction vector, it will return the distance
     * traveled by this component of the direction to reach the block with a different X coordinate.
     *
     * Find the smallest positive t such that s + t*ds is an integer.
     *
     * @param float $s Starting coordinate
     * @param float $ds The component of the direction vector along the axis
     *
     * @return float The ray trace distance that needs to be traveled to cross the boundary.
     */
    private static function rayTraceDistanceToBoundary(float $s, float $ds) : float{
        if($ds == 0){
            return INF;
        }

        if($ds < 0){
            $s = -$s;
            $ds = -$ds;

            if(floor($s) == $s){ // exactly on the coordinate, will immediately leave the coordinate moving in the negative direction
                return 0;
            }
        }

        // The problem now is to find the smallest positive t such that s + t*ds = 1
        return (1 - ($s - floor($s))) / $ds;
    }
}
