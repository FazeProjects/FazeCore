<?php



/**
 * Classes related to events
 */

namespace pocketmine\event;

abstract class Event {

    /**
     * Any triggered event must declare the following static variables:
     *
     * public static $handlerList = null;
     * public static $eventPool = [];
     * public static $nextEvent = 0;
     *
     * If this is not done, proper event initialization will be prevented.
     */

    protected $eventName = null;
    private $isCancelled = false;

    /**
     * @return string
     */
    final public function getEventName(){
        return $this->eventName === null ? get_class($this) : $this->eventName;
    }

    /**
     * @return bool
     *
     * @throws \BadMethodCallException
     */
    public function isCancelled(){
        if(!($this instanceof Cancellable)){
            throw new \BadMethodCallException(get_class($this) . " cannot be cancelled");
        }

        return $this->isCancelled;
    }

    /**
     * @param bool $value
     *
     * @throws \BadMethodCallException
     */
    public function setCancelled($value = true){
        if(!($this instanceof Cancellable)){
            throw new \BadMethodCallException(get_class($this) . " cannot be cancelled");
        }

        $this->isCancelled = (bool) $value;
    }

    /**
     * @return HandlerList
     */
    public function getHandlers(){
        if(static::$handlerList === null){
            static::$handlerList = new HandlerList();
        }

        return static::$handlerList;
    }

}
