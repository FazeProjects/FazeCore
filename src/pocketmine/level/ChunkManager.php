<?php



declare(strict_types=1);

namespace pocketmine\level;

use pocketmine\level\format\Chunk;

interface ChunkManager {
	/**
	 * Gets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-255
	 */
	public function getBlockIdAt(int $x, int $y, int $z) : int;

	/**
	 * Sets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id 0-255
	 */
	public function setBlockIdAt(int $x, int $y, int $z, int $id);

	/**
	 * Gets the raw block metadata
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockDataAt(int $x, int $y, int $z) : int;

	/**
	 * Sets the raw block metadata.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data 0-15
	 */
	public function setBlockDataAt(int $x, int $y, int $z, int $data);

	/**
	 * Gets the raw block light level
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockLightAt(int $x, int $y, int $z) : int;

	/**
	 * Updates the light around the block
	 *
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function updateBlockLight(int $x, int $y, int $z);

	/**
	 * Sets the raw block light level.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level 0-15
	 */
	public function setBlockLightAt(int $x, int $y, int $z, int $level);

	/**
	 * Returns the highest amount of sky light can reach the specified coordinates.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockSkyLightAt(int $x, int $y, int $z) : int;

	/**
	 * Sets the raw block sky light level.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level
	 */
	public function setBlockSkyLightAt(int $x, int $y, int $z, int $level);

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return Chunk|null
	 */
	public function getChunk(int $chunkX, int $chunkZ);

	/**
	 * @param int   $chunkX
	 * @param int   $chunkZ
	 * @param Chunk $chunk
	 */
	public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk = null);

	/**
	 * Gets the level seed
	 *
	 * @return int|string
	 */
	public function getSeed();

	/**
	 * Returns the height of the world
	 * @return int
	 */
	public function getWorldHeight() : int;

	/**
	 * Returns whether the specified coordinates are within the valid world boundaries, taking world format limitations
	 * into account.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return bool
	 */
	public function isInWorld(int $x, int $y, int $z) : bool;
}