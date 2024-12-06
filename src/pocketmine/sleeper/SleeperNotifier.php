<?php



declare(strict_types=1);

namespace pocketmine\sleeper;

use function assert;

/**
 * Notifiers are Threaded objects that can be attached to threaded sleepers to wake them up.
 */
class SleeperNotifier extends \Threaded{
	/** @var \Threaded */
	private $sharedObject;

	/** @var int */
	private $sleeperId;

	final public function attachSleeper(\Threaded $sharedObject, int $id) : void{
		$this->sharedObject = $sharedObject;
		$this->sleeperId = $id;
	}

	final public function getSleeperId() : int{
		return $this->sleeperId;
	}

	final public function wakeupSleeper() : void{
		$shared = $this->sharedObject;
		assert($shared !== null);
		$sleeperId = $this->sleeperId;
		$shared->synchronized(function() use ($shared, $sleeperId) : void{
			if(!isset($shared[$sleeperId])){
				$shared[$sleeperId] = $sleeperId;
				$shared->notify();
			}
		});
	}
}