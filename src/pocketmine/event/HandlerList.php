<?php



namespace pocketmine\event;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\RegisteredListener;

class HandlerList {

	/**
	 * @var RegisteredListener[]
	 */
	private $handlers = null;

	/**
	 * @var RegisteredListener[][]
	 */
	private $handlerSlots = [];

	/**
	 * @var HandlerList[]
	 */
	private static $allLists = [];

	public static function bakeAll(){
		foreach(self::$allLists as $h){
			$h->bake();
		}
	}

    /**
     * Unregisters all listeners
     * If a plugin or listener is passed, all listeners with that object will be removed.
     *
     * @param Plugin|Listener|null $object
     */
	public static function unregisterAll($object = null){
		if($object instanceof Listener or $object instanceof Plugin){
			foreach(self::$allLists as $h){
				$h->unregister($object);
			}
		}else{
			foreach(self::$allLists as $h){
				foreach($h->handlerSlots as $key => $list){
					$h->handlerSlots[$key] = [];
				}
				$h->handlers = null;
			}
		}
	}

	/**
	 * HandlerList constructor.
	 */
	public function __construct(){
		$this->handlerSlots = [
			EventPriority::LOWEST => [],
			EventPriority::LOW => [],
			EventPriority::NORMAL => [],
			EventPriority::HIGH => [],
			EventPriority::HIGHEST => [],
			EventPriority::MONITOR => []
		];
		self::$allLists[] = $this;
	}

	/**
	 * @param RegisteredListener $listener
	 *
	 * @throws \Throwable
	 */
	public function register(RegisteredListener $listener){
		if($listener->getPriority() < EventPriority::MONITOR or $listener->getPriority() > EventPriority::LOWEST){
			return;
		}
		if(isset($this->handlerSlots[$listener->getPriority()][spl_object_hash($listener)])){
			throw new \InvalidStateException("This listener is already registered as a priority " . $listener->getPriority());
		}
		$this->handlers = null;
		$this->handlerSlots[$listener->getPriority()][spl_object_hash($listener)] = $listener;
	}

	/**
	 * @param RegisteredListener[] $listeners
	 */
	public function registerAll(array $listeners){
		foreach($listeners as $listener){
            try {
                $this->register($listener);
            } catch (\Throwable $e) {
            }
        }
	}

	/**
	 * @param RegisteredListener|Listener|Plugin $object
	 */
	public function unregister($object){
		if($object instanceof Plugin or $object instanceof Listener){
			$changed = false;
			foreach($this->handlerSlots as $priority => $list){
				foreach($list as $hash => $listener){
					if(($object instanceof Plugin and $listener->getPlugin() === $object)
						or ($object instanceof Listener and $listener->getListener() === $object)
					){
						unset($this->handlerSlots[$priority][$hash]);
						$changed = true;
					}
				}
			}
			if($changed === true){
				$this->handlers = null;
			}
		}elseif($object instanceof RegisteredListener){
			if(isset($this->handlerSlots[$object->getPriority()][spl_object_hash($object)])){
				unset($this->handlerSlots[$object->getPriority()][spl_object_hash($object)]);
				$this->handlers = null;
			}
		}
	}

	public function bake(){
		if($this->handlers !== null){
			return;
		}
		$entries = [];
		foreach($this->handlerSlots as $list){
			foreach($list as $hash => $listener){
				$entries[$hash] = $listener;
			}
		}
		$this->handlers = $entries;
	}

	/**
	 * @param null|Plugin $plugin
	 *
	 * @return RegisteredListener[]
	 */
	public function getRegisteredListeners($plugin = null){
		if($plugin !== null){
			$listeners = [];
			foreach($this->getRegisteredListeners() as $hash => $listener){
				if($listener->getPlugin() === $plugin){
					$listeners[$hash] = $plugin;
				}
			}

			return $listeners;
		}else{
			while(($handlers = $this->handlers) === null){
				$this->bake();
			}

			return $handlers;
		}
	}

	/**
	 * @return HandlerList[]
	 */
	public static function getHandlerLists(){
		return self::$allLists;
	}

}
