<?php



namespace pocketmine\scheduler;

use pocketmine\utils\MainLogger;
use pocketmine\utils\Utils;
use pocketmine\thread\Worker;
use function error_reporting;
use function gc_enable;
use function ini_set;
use function set_error_handler;

class AsyncWorker extends Worker{
    /** @var array */
    private static $store = [];

    /** @var \ThreadedLogger */
    private $logger;
    /** @var int */
    private $id;

    /** @var int */
    private $memoryLimit;

    public function __construct(\ThreadedLogger $logger, int $id, int $memoryLimit){
        $this->logger = $logger;
        $this->id = $id;
        $this->memoryLimit = $memoryLimit;
    }

    public function run(){
        error_reporting(-1);

        $this->registerClassLoader();

        // Set this after the autoloader is registered
        set_error_handler([Utils::class, 'errorExceptionHandler']);

        if($this->logger instanceof MainLogger){
            $this->logger->registerStatic();
        }

        gc_enable();

        if($this->memoryLimit > 0){
            ini_set('memory_limit', $this->memoryLimit . 'M');
            $this->logger->debug("Set memory limit to " . $this->memoryLimit . " MB");
        }else{
            ini_set('memory_limit', '-1');
            $this->logger->debug("No memory limit set");
        }
    }

    public function getLogger() : \ThreadedLogger{
        return $this->logger;
    }

    /**
     * @return void
     */
    public function handleException(\Throwable $e){
        $this->logger->logException($e);
    }

    /**
     * @return string
     */
    public function getThreadName(){
        return "Asynchronous Worker #" . $this->id;
    }

    public function getAsyncWorkerId() : int{
        return $this->id;
    }

    /**
     * Saves mixed data to the thread's local object store. This can be used to store objects you want to use in this worker thread
     * across multiple AsyncTasks.
     *
     * @param mixed  $value
     */
    public function saveToThreadStore(string $identifier, $value) : void{
        self::$store[$identifier] = $value;
    }

    /**
     * Retrieves mixed data from the thread's local object store.
     *
     * Note that the thread's local object store can be cleared, and your data may not exist anymore, so your code should
     * handle the possibility that what you're trying to retrieve may not exist.
     *
     * Objects stored in this store can only be retrieved during task execution.
     *
     * @return mixed
     */
    public function getFromThreadStore(string $identifier){
        return self::$store[$identifier] ?? null;
    }

    /**
     * Removes previously saved mixed data from the thread's local object store.
     */
    public function removeFromThreadStore(string $identifier) : void{
        unset(self::$store[$identifier]);
    }
}
