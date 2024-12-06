<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\nbt\NBT;
use pocketmine\nbt\JsonNBTParser;
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;

class SummonCommand extends VanillaCommand {

    /**
     * SummonCommand constructor.
     *
     * @param $name
     */
    public function __construct($name){
        parent::__construct(
            $name,
            "Summon an entity at specified coordinates.",
            "/summon [entity] [<x> <y> <z>] [NBT Data]"
        );
        $this->setPermission("pocketmine.command.summon");
    }

    /**
     * @param CommandSender $sender
     * @param string        $currentAlias
     * @param array         $args
     *
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if (!$this->testPermission($sender)) {
            return true;
        }

        // Validate arguments count
        if (count($args) !== 1 && count($args) !== 4 && count($args) !== 5) {
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
            return true;
        }

        $x = $y = $z = 0;

        // Determine coordinates
        if (count($args) >= 4) {
            // Extract coordinates from arguments
            [$x, $y, $z] = $this->parseCoordinates($sender, $args[1], $args[2], $args[3]);
            if ($x === null || $y === null || $z === null) {
                return false; // Errors are handled in parseCoordinates
            }
        } elseif ($sender instanceof Player) {
            $x = $sender->x;
            $y = $sender->y;
            $z = $sender->z;
        } else {
            $sender->sendMessage(TextFormat::RED . "You must specify a position to spawn the entity when using the console.");
            return false;
        }

        $type = $args[0];
        $level = ($sender instanceof Player) ? $sender->getLevel() : $sender->getServer()->getDefaultLevel();

        // Create NBT data
        $nbt = new CompoundTag("", [
            "Pos" => new ListTag("Pos", [
                new DoubleTag("", $x),
                new DoubleTag("", $y),
                new DoubleTag("", $z)
            ]),
            "Motion" => new ListTag("Motion", [
                new DoubleTag("", 0),
                new DoubleTag("", 0),
                new DoubleTag("", 0)
            ]),
            "Rotation" => new ListTag("Rotation", [
                new FloatTag("", lcg_value() * 360),
                new FloatTag("", 0)
            ]),
        ]);

        // Handle additional NBT data
        if (count($args) === 5 && $args[4][0] === "{") {
            try {
                $extraNbt = JsonNBTParser::parseJSON($args[4]);
                $nbt = NBT::combineCompoundTags($nbt, $extraNbt, true);
            } catch (\Exception $e) {
                $sender->sendMessage(TextFormat::RED . "Invalid NBT data: " . $e->getMessage());
                return false;
            }
        }

        // Create and spawn the entity
        $entity = Entity::createEntity($type, $level, $nbt);
        if ($entity instanceof Entity) {
            $entity->spawnToAll();
            $sender->sendMessage(TextFormat::GREEN . "Successfully spawned entity '$type' at ($x, $y, $z).");
            return true;
        } else {
            $sender->sendMessage(TextFormat::RED . "Failed to spawn entity '$type'.");
            return false;
        }
    }

    /**
     * Parse and validate coordinates.
     *
     * @param CommandSender $sender
     * @param string $xArg
     * @param string $yArg
     * @param string $zArg
     *
     * @return array|null
     */
    private function parseCoordinates(CommandSender $sender, $xArg, $yArg, $zArg){
        $x = $this->parseCoordinate($sender, $xArg, $sender->x ?? 0);
        $y = $this->parseCoordinate($sender, $yArg, $sender->y ?? 0, 0, 256);
        $z = $this->parseCoordinate($sender, $zArg, $sender->z ?? 0);

        if ($x === null || $y === null || $z === null) {
            $sender->sendMessage(TextFormat::RED . "Invalid coordinates.");
            return null;
        }

        return [$x, $y, $z];
    }

    /**
     * Parse a single coordinate value.
     *
     * @param CommandSender $sender
     * @param string $value
     * @param float $base
     * @param float|null $min
     * @param float|null $max
     *
     * @return float|null
     */
    private function parseCoordinate(CommandSender $sender, $value, $base, $min = null, $max = null){
        if (is_numeric($value)) {
            return $value;
        }

        if (strpos($value, "~") === 0) {
            $offset = substr($value, 1);
            $result = is_numeric($offset) ? $base + $offset : $base;

            if ($min !== null && $result < $min) {
                $sender->sendMessage(TextFormat::RED . "Coordinate out of bounds: $result < $min");
                return null;
            }

            if ($max !== null && $result > $max) {
                $sender->sendMessage(TextFormat::RED . "Coordinate out of bounds: $result > $max");
                return null;
            }

            return $result;
        }

        return null;
    }
}
