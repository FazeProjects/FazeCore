<?php



namespace pocketmine\event\level;

use pocketmine\event\Cancellable;
use pocketmine\level\Level;
use pocketmine\level\weather\Weather;

class WeatherChangeEvent extends LevelEvent implements Cancellable {
	public static $handlerList = null;

	private $weather;
	private $duration;

	/**
	 * WeatherChangeEvent constructor.
	 *
	 * @param Level $level
	 * @param int   $weather
	 * @param int   $duration
	 */
	public function __construct(Level $level, int $weather, int $duration){
		parent::__construct($level);
		$this->weather = $weather;
		$this->duration = $duration;
	}

	/**
	 * @return int
	 */
	public function getWeather() : int{
		return $this->weather;
	}

	/**
	 * @param int $weather
	 */
	public function setWeather(int $weather = Weather::SUNNY){
		$this->weather = $weather;
	}

	/**
	 * @return int
	 */
	public function getDuration() : int{
		return $this->duration;
	}

	/**
	 * @param int $duration
	 */
	public function setDuration(int $duration){
		$this->duration = $duration;
	}

}