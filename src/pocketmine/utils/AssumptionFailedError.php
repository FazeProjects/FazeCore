<?php



declare(strict_types=1);

namespace pocketmine\utils;

/**
 * This exception should be thrown in places where something is assumed to be true, but the type system does not guarantee it.
 * This makes static analyzers happy and ensures that the server will crash properly if any assumption fails.
 */
final class AssumptionFailedError extends \Error{

}