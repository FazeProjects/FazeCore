<?php

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;

class EntityRideEvent extends EntityEvent implements Cancellable
{
	public static $handlerList = null;

	private $rider;

	public function __construct(Entity $rideable, Entity $rider)
	{
		$this->entity = $rideable;
		$this->rider = $rider;
	}

	public function getRider(): Entity
	{
		return $this->rider;
	}
}