<?php



namespace pocketmine\event\player;

use pocketmine\entity\Human;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerExhaustEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	const CAUSE_ATTACK = 1;
	const CAUSE_DAMAGE = 2;
	const CAUSE_MINING = 3;
	const CAUSE_HEALTH_REGEN = 4;
	const CAUSE_POTION = 5;
	const CAUSE_WALKING = 6;
	const CAUSE_SPRINTING = 7;
	const CAUSE_SWIMMING = 8;
	const CAUSE_JUMPING = 9;
	const CAUSE_SPRINT_JUMPING = 10;
	const CAUSE_CUSTOM = 11;

	/** @var float */
	private $amount;
	/** @var int */
	private $cause;

	/**
	 * PlayerExhaustEvent constructor.
	 *
	 * @param Human $human
	 * @param float $amount
	 * @param int   $cause
	 */
	public function __construct(Human $human, float $amount, int $cause){
		$this->player = $human;
		$this->amount = $amount;
		$this->cause = $cause;
	}

	/**
	 * @return Human
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * @return float
	 */
	public function getAmount() : float{
		return $this->amount;
	}

	/**
	 * @param float $amount
	 */
	public function setAmount(float $amount){
		$this->amount = $amount;
	}

    /**
     * Returns an int indicating the exhaustion reason - one of the constants at the top of this class.
     */
	public function getCause() : int{
		return $this->cause;
	}
}
