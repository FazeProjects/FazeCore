<?php



/**
 * Event called when a player tries to use
 * some bugs or cheats to move illegally.
 */

namespace pocketmine\event\player\cheat;

use pocketmine\event\Cancellable;
use pocketmine\Player;
use pocketmine\math\Vector3;

class PlayerIllegalMoveEvent extends PlayerCheatEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Vector3 */
	private $attemptedPosition;
	/** @var Vector3 */
	private $originalPosition;
	/** @var Vector3 */
	private $expectedPosition;

	/**
	 * @param Player  $player
	 * @param Vector3 $attemptedPosition
	 * @param Vector3 $originalPosition
	 */
	public function __construct(Player $player, Vector3 $attemptedPosition, Vector3 $originalPosition){
		$this->player = $player;
		$this->attemptedPosition = $attemptedPosition;
		$this->originalPosition = $originalPosition;
		$this->expectedPosition = $player->asVector3();
	}

	/**
	 * @return Vector3
	 */
	public function getAttemptedPosition() : Vector3{
		return $this->attemptedPosition;
	}

	/**
	 * @return Vector3
	 */
	public function getOriginalPosition() : Vector3{
		return $this->originalPosition;
	}

	/**
	 * @return Vector3
	 */
	public function getExpectedPosition() : Vector3{
		return $this->expectedPosition;
	}
}