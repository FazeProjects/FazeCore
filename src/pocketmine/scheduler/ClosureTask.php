<?php



namespace pocketmine\scheduler;

use pocketmine\utils\Utils;

/**
 * Implementation of a task that allows the scheduler to call closures.
 *
 * Example of use:
 *
 * ```
 * TaskScheduler->scheduleTask(new ClosureTask(function($currentTick) : void {
 * echo "Hello on $currentTick\n";
 * });
 * ```
 */
class ClosureTask extends Task{

	/**
	 * @var \Closure
	 * @phpstan-var \Closure(int) : void
	 */
	private $closure;

	/**
	 * @param \Closure $closure Must accept only ONE parameter, $currentTick
	 * @phpstan-param \Closure(int) : void $closure
	 */
	public function __construct(\Closure $closure){
		$this->closure = $closure;
	}

	public function getName() : string{
        try {
            return Utils::getNiceClosureName($this->closure);
        } catch (\ReflectionException $e) {
        }
        return false;
    }

	public function onRun($currentTick){
		($this->closure)($currentTick);
	}
}