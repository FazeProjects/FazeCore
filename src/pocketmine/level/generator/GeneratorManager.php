<?php



declare(strict_types=1);

namespace pocketmine\level\generator;

use pocketmine\level\generator\hell\Nether;
use pocketmine\level\generator\ender\Ender;
use pocketmine\level\generator\normal\Normal;

final class GeneratorManager{

	private static $list = [];

	public static function registerDefaultGenerators() : void{
		self::addGenerator(Flat::class, "flat");
        self::addGenerator(Normal::class, "normal");
        self::addGenerator(Normal::class, "default");
        self::addGenerator(Nether::class, "hell");
        self::addGenerator(Nether::class, "nether");
        self::addGenerator(VoidGenerator::class, "void");
        self::addGenerator(Ender::class, "ender");
	}

	public static function addGenerator($object, $name) : bool{
		if(is_subclass_of($object, Generator::class) and !isset(self::$list[$name = strtolower($name)])){
			self::$list[$name] = $object;

			return true;
		}

		return false;
	}

	/**
	 * @return string[]
	 */
	public static function getGeneratorList() : array{
		return array_keys(self::$list);
	}

	/**
	 * Returns a class name of a registered Generator matching the given name.
	 *
	 * @param string $name
	 * @param bool   $throwOnMissing @deprecated this is for backwards compatibility only
	 *
	 * @return string|Generator Name of class that extends Generator (not an actual Generator object)
	 * @throws \InvalidArgumentException if the generator type isn't registered
	 */
	public static function getGenerator(string $name, bool $throwOnMissing = false){
		if(isset(self::$list[$name = strtolower($name)])){
			return self::$list[$name];
		}

		if($throwOnMissing){
			throw new \InvalidArgumentException("Alias \"$name\" does not map to any known generator");
		}
		return Normal::class;
	}

	public static function getGeneratorName($class){
		foreach(self::$list as $name => $c){
			if($c === $class){
				return $name;
			}
		}

		return "unknown";
	}

	private function __construct(){
		//NOOP
	}
}