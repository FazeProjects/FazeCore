<?php



namespace pocketmine\utils;

use pocketmine\thread\Thread;
use function getmypid;
use function time;

class ServerKiller extends Thread{

	public $time;

	/** @var bool */
	private $stopped = false;

	/**
     * Конструктор ServerKiller.
	 *
	 * @param int $time
	 */
	public function __construct($time = 15){
		$this->time = $time;
	}

	public function run(){
		$this->registerClassLoader();
		$start = time();
		$this->synchronized(function(){
			if(!$this->stopped){
			    $this->wait($this->time * 1000000);
		    }
		});
		if(time() - $start >= $this->time){
			echo "\nToo long stopped, server was force killed!\n";
			@Utils::kill(getmypid());
		}
	}

	public function quit() : void{
		$this->synchronized(function() : void{
			$this->stopped = true;
			$this->notify();
		});
		parent::quit();
	}

	/**
	 * @return string
	 */
	public function getThreadName(){
		return "Server Killer";
	}
}
