<?php


 
namespace pocketmine\event;


/**
 * Event priority list
 *
 * Events will be called in this order:
 * LOWEST -> LOW -> NORMAL -> HIGH -> HIGH -> MONITOR
 *
 * MONITOR events should not change the result or content of the event
 */
abstract class EventPriority {
    public const ALL = [
        self::LOWEST,
        self::LOW,
        self::NORMAL,
        self::HIGH,
        self::HIGHEST,
        self::MONITOR
    ];

    /**
     * The event call has very low significance and should be executed first to allow
     * other plugins to further adjust the outcome.
     */
    const LOWEST = 5;
    /**
     * The event call is not of great importance.
     */
    const LOW = 4;
    /**
     * The event call is neither important nor unimportant, and can be executed normally.
     * This is the default priority.
     */
    const NORMAL = 3;
    /**
     * The event call is of significant importance.
     */
    const HIGH = 2;
    /**
     * The event declaration is crucial and must have the final say in what happens
     * to the event.
     */
    const HIGHEST = 1;
    /**
     * The event is only listened to for observing the outcome of the event.
     *
     * No changes should be made to the event with this priority.
     */
    const MONITOR = 0;

    /**
     * @param string $name
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $name) : int{
        $name = strtoupper($name);
        $const = self::class . "::" . $name;
        if($name !== "ALL" and \defined($const)){
            return \constant($const);
        }

        throw new \InvalidArgumentException("Could not determine priority \"$name\"");
    }
}
