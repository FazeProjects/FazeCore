<?php



namespace pocketmine\thread;

use pocketmine\Server;
use const PTHREADS_INHERIT_ALL;

/**
 * This class must be extended by all custom threading classes
 */
abstract class Thread extends \Thread{

	/** @var \ClassLoader|null */
	protected $classLoader;

	/** @var bool */
	protected $isKilled = false;

	/**
	 * @return \ClassLoader|null
	 */
	public function getClassLoader(){
		return $this->classLoader;
	}

	/**
	 * @return void
	 */
	public function setClassLoader(\ClassLoader $loader = null){
		if($loader === null){
			$loader = Server::getInstance()->getLoader();
		}
		$this->classLoader = $loader;
	}

	/**
	 * Registers the class loader for this thread.
	 *
	 * WARNING: This method MUST be called from any descendent threads' run() method to make autoloading usable.
	 * If you do not do this, you will not be able to use new classes that were not loaded when the thread was started
	 * (unless you are using a custom autoloader).
	 *
	 * @return void
	 */
	public function registerClassLoader(){
		if($this->classLoader !== null){
			$this->classLoader->register(true);
		}
	}

	/**
	 * @return bool
	 */
	public function start(int $options = PTHREADS_INHERIT_ALL){
		ThreadManager::getInstance()->add($this);

		if($this->getClassLoader() === null){
			$this->setClassLoader();
		}
		
		return parent::start($options);
	}

	/**
	 * Stops the thread using the best way possible. Try to stop it yourself before calling this.
	 *
	 * @return void
	 */
	public function quit(){
		$this->isKilled = true;

		if(!$this->isJoined()){
			$this->notify();
			$this->join();
		}

		ThreadManager::getInstance()->remove($this);
	}

	/**
	 * @return string
	 */
	public function getThreadName(){
		return (new \ReflectionClass($this))->getShortName();
	}
}