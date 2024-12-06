<?php


 
namespace pocketmine\plugin;

use pocketmine\event\Event;
use pocketmine\event\Listener;

class MethodEventExecutor implements EventExecutor {

	private $method;

	/**
     * MethodEventExecutor constructor.
	 *
	 * @param $method
	 */
	public function __construct($method){
		$this->method = $method;
	}

	/**
	 * @param Listener $listener
	 * @param Event    $event
	 */
	public function execute(Listener $listener, Event $event){
		$listener->{$this->getMethod()}($event);
	}

	/**
	 * @return mixed
	 */
	public function getMethod(){
		return $this->method;
	}
}