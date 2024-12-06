<?php



declare(strict_types=1);

namespace pocketmine\command;

use pocketmine\sleeper\SleeperNotifier;
use pocketmine\thread\Thread;
use function fclose;
use function fgets;
use function fopen;
use function fstat;
use function is_resource;
use function microtime;
use function preg_replace;
use function readline;
use function stream_isatty;
use function stream_select;
use function trim;
use function usleep;

class CommandReader extends Thread {

    const TYPE_READLINE = 0;
    const TYPE_STREAM = 1;
    const TYPE_PIPED = 2;

    /** @var resource */
    private static $stdin;

    /** @var \Threaded */
    protected $buffer;
    /** @var bool */
    private $shutdown = false;
    /** @var int */
    private $type = self::TYPE_STREAM;

    /** @var SleeperNotifier|null */
    private $notifier;

    public function __construct(?SleeperNotifier $notifier = null){
        $this->buffer = new \Threaded;
        $this->notifier = $notifier;

        $this->setClassLoader();
    }

    /**
     * @return void
     */
    public function shutdown(){
        $this->shutdown = true;
    }

    public function quit(){
        $wait = microtime(true) + 0.5;
        while(microtime(true) < $wait){
            if($this->isRunning()){
                usleep(100000);
            } else {
                parent::quit();
                return;
            }
        }

        $message = "The thread is blocked for an unknown reason.";
        if($this->type === self::TYPE_PIPED){
            $message = "STDIN is coming from another source, and the pipe is blocked and cannot be safely stopped.";
        }

        throw new \ThreadException($message);
    }

    private function initStdin() : void {
        if(is_resource(self::$stdin)){
            fclose(self::$stdin);
        }

        self::$stdin = fopen("php://stdin", "r");
        if($this->isPipe(self::$stdin)){
            $this->type = self::TYPE_PIPED;
        } else {
            $this->type = self::TYPE_STREAM;
        }
    }

    /**
     * Checks if the given stream is a FIFO pipe.
     *
     * @param resource $stream
     *
     * @return bool
     */
    private function isPipe($stream) : bool {
        return is_resource($stream) and (!stream_isatty($stream) or ((fstat($stream)["mode"] & 0170000) === 0010000));
    }

    /**
     * Reads a line from the console and adds it to the buffer. This method can block the thread.
     *
     * @return bool if the main execution should continue reading lines
     */
    private function readLine() : bool {
        if(!is_resource(self::$stdin)){
            $this->initStdin();
        }

        $r = [self::$stdin];
        $w = $e = null;
        if(($count = stream_select($r, $w, $e, 0, 200000)) === 0){ // nothing changed in 200000 microseconds
            return true;
        } elseif($count === false){ // stream error
            $this->initStdin();
        }

        if(($raw = fgets(self::$stdin)) === false){ // broken pipe or EOF
            $this->initStdin();
            $this->synchronized(function() : void {
                $this->wait(200000);
            }); // prevent CPU waste if it's end of pipe
            return true; // loop back round
        }

        $line = trim($raw);

        if($line !== ""){
            $this->buffer[] = preg_replace("#\\x1b\\x5b([^\\x1b]*\\x7e|[\\x40-\\x50])#", "", $line);
            if($this->notifier !== null){
                $this->notifier->wakeupSleeper();
            }
        }

        return true;
    }

    /**
     * Reads a line from the console if available. Returns null if not available.
     *
     * @return string|null
     */
    public function getLine(){
        if($this->buffer->count() !== 0){
            return $this->buffer->shift();
        }

        return null;
    }

    public function run(){
        $this->registerClassLoader();

        while(!$this->shutdown and $this->readLine());

        fclose(self::$stdin);
    }

    /**
     * @return string
     */
    public function getThreadName(){
        return "Console";
    }
}
