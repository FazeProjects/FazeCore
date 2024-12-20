<?php



namespace pocketmine\utils;

use pocketmine\Server;


/**
 * Configuration class for easy configuration management of multiple formats.
 */
class Config {
	const DETECT = -1; //Detect by file extension
	const PROPERTIES = 0; // .properties
	const CNF = Config::PROPERTIES; // .cnf
	const JSON = 1; // .js, .json
	const YAML = 2; // .yml, .yaml
	//const EXPORT = 3; // .export, .xport
	const SERIALIZED = 4; // .sl
	const ENUM = 5; // .txt, .list, .enum
	const ENUMERATION = Config::ENUM;

	/** @var array */
	private $config = [];

	private $nestedCache = [];

	/** @var string */
	private $file;
	/** @var bool */
	private $correct = false;
	/** @var int */
	private $type = Config::DETECT;
	/** @var int */
	private $jsonOptions = JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;

	/** @var bool */
	private $changed = false;

	public static $formats = [
		"properties" => Config::PROPERTIES,
		"cnf" => Config::CNF,
		"conf" => Config::CNF,
		"config" => Config::CNF,
		"json" => Config::JSON,
		"js" => Config::JSON,
		"yml" => Config::YAML,
		"yaml" => Config::YAML,
		//"export" => Config::EXPORT,
		//"xport" => Config::EXPORT,
		"sl" => Config::SERIALIZED,
		"serialize" => Config::SERIALIZED,
		"txt" => Config::ENUM,
		"list" => Config::ENUM,
		"enum" => Config::ENUM,
	];

	/**
	 * @param string $file     Path of the file to be loaded
	 * @param int    $type     Config type to load, -1 by default (detect)
	 * @param array  $default  Array with the default values that will be written to the file if it did not exist
	 * @param null   &$correct Sets correct to true if everything has been loaded correctly
	 */
	public function __construct($file, $type = Config::DETECT, $default = [], &$correct = null){
		$this->load($file, $type, $default);
		$correct = $this->correct;
	}

	/**
	 * Удаляет все изменения в памяти и снова загружает файл
	 */
	public function reload(){
		$this->config = [];
		$this->nestedCache = [];
		$this->correct = false;
		$this->load($this->file, $this->type);
	}

	public function hasChanged() : bool{
		return $this->changed;
	}

	public function setChanged(bool $changed = true) : void{
		$this->changed = $changed;
	}

	/**
	 * @param $str
	 *
	 * @return mixed
	 */
	public static function fixYAMLIndexes($str){
		return preg_replace("#^([ ]*)([a-zA-Z_]{1}[ ]*)\\:$#m", "$1\"$2\":", $str);
	}

	/**
	 * @param string $file
	 * @param int    $type
	 * @param array  $default
	 *
	 * @return bool
	 */
	public function load(string $file, int $type = Config::DETECT, array $default = []){
		$this->correct = true;
		$this->file = $file;
		$this->type = $type;
		if($this->type === Config::DETECT){
			$extension = explode(".", basename($this->file));
			$extension = strtolower(trim(array_pop($extension)));
			if(isset(Config::$formats[$extension])){
				$this->type = Config::$formats[$extension];
			}else{
				$this->correct = false;
			}
		}

		if(!file_exists($file)){
			$this->config = $default;
			$this->save();
		}else{
			if($this->correct === true){
				$content = file_get_contents($this->file);
				switch($this->type){
					case Config::PROPERTIES:
					case Config::CNF:
						$this->parseProperties($content);
						break;
					case Config::JSON:
						$this->config = json_decode($content, true);
						break;
					case Config::YAML:
						$content = self::fixYAMLIndexes($content);
						$this->config = yaml_parse($content);
						break;
					case Config::SERIALIZED:
						$this->config = unserialize($content);
						break;
					case Config::ENUM:
						$this->parseList($content);
						break;
					default:
						$this->correct = false;

						return false;
				}
				if(!is_array($this->config)){
					$this->config = $default;
				}
				if($this->fillDefaults($default, $this->config) > 0){
					$this->save();
				}
			}else{
				return false;
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function check(){
		return $this->correct === true;
	}

	/**
	 * @return bool
	 */
	public function save(){
		if($this->correct === true){
			$content = null;
			switch($this->type){
				case Config::PROPERTIES:
					$content = $this->writeProperties();
					break;
				case Config::JSON:
					$content = json_encode($this->config, $this->jsonOptions);
					break;
				case Config::YAML:
					$content = yaml_emit($this->config, YAML_UTF8_ENCODING);
					break;
				case Config::SERIALIZED:
					$content = serialize($this->config);
					break;
				case Config::ENUM:
					$content = implode("\r\n", array_keys($this->config));
					break;
				default:
					throw new \InvalidStateException("Configuration type unknown, not installed, or not detected");
			}

			file_put_contents($this->file, $content);

			$this->changed = false;

			return true;
		}else{
			return false;
		}
	}

	/**
	 * Returns the path to the config.
	 *
	 * @return string
	 */
	public function getPath() : string{
		return $this->file;
	}

	/**
	 * Sets JSON encoding options when saving
	 *
	 * @param int $options
	 * @return Config $this
	 * @throws \RuntimeException if the Config is not in JSON
	 * @see json_encode
	 */
	public function setJsonOptions(int $options) : Config{
		if($this->type !== Config::JSON){
			throw new \RuntimeException("Attempting to set JSON options for a non-JSON configuration.");
		}
		$this->jsonOptions = $options;
		return $this;
	}

	/**
	 * Enables this option in addition to the currently set JSON options.
	 *
	 * @param int $option
	 * @return Config $this
	 * @throws \RuntimeException if the Config is not in JSON
	 * @see json_encode
	 */
	public function enableJsonOption(int $option) : Config{
		if($this->type !== Config::JSON){
			throw new \RuntimeException("Attempting to enable the JSON option for a non-JSON configuration.");
		}
		$this->jsonOptions |= $option;
		return $this;
	}

	/**
	 * Disables this option for JSON encoding when saving.
	 *
	 * @param int $option
	 * @return Config $this
	 * @throws \RuntimeException if the Config is not in JSON
	 * @see json_encode
	 */
	public function disableJsonOption(int $option) : Config{
		if($this->type !== Config::JSON){
			throw new \RuntimeException("Attempting to disable the JSON option for a non-JSON configuration.");
		}
		$this->jsonOptions &= ~$option;
		return $this;
	}

	/**
	 * Returns JSON encoding parameters when saving
	 *
	 * @return int
	 * @throws \RuntimeException if the Config is not in JSON
	 * @see json_encode
	 */
	public function getJsonOptions() : int{
		if($this->type !== Config::JSON){
			throw new \RuntimeException("Attempting to disable the JSON option for a non-JSON configuration.");
		}
		return $this->jsonOptions;
	}

	/**
	 * @param $k
	 *
	 * @return bool|mixed
	 */
	public function __get($k){
		return $this->get($k);
	}

	/**
	 * @param $k
	 * @param $v
	 */
	public function __set($k, $v){
		$this->set($k, $v);
	}

	/**
	 * @param $k
	 *
	 * @return bool
	 */
	public function __isset($k){
		return $this->exists($k);
	}

	/**
	 * @param $k
	 */
	public function __unset($k){
		$this->remove($k);
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function setNested($key, $value){
		$vars = explode(".", $key);
		$base = array_shift($vars);

		if(!isset($this->config[$base])){
			$this->config[$base] = [];
		}

		$base =& $this->config[$base];

		while(count($vars) > 0){
			$baseKey = array_shift($vars);
			if(!isset($base[$baseKey])){
				$base[$baseKey] = [];
			}
			$base =& $base[$baseKey];
		}

		$base = $value;
		$this->nestedCache = [];
		$this->changed = true;
	}

	/**
	 * @param       $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getNested($key, $default = null){
		if(isset($this->nestedCache[$key])){
			return $this->nestedCache[$key];
		}

		$vars = explode(".", $key);
		$base = array_shift($vars);
		if(isset($this->config[$base])){
			$base = $this->config[$base];
		}else{
			return $default;
		}

		while(count($vars) > 0){
			$baseKey = array_shift($vars);
			if(is_array($base) and isset($base[$baseKey])){
				$base = $base[$baseKey];
			}else{
				return $default;
			}
		}

		return $this->nestedCache[$key] = $base;
	}

	public function removeNested(string $key) : void{
		$this->nestedCache = [];

		$vars = explode(".", $key);

		$currentNode =& $this->config;
		while(count($vars) > 0){
			$nodeName = array_shift($vars);
			if(isset($currentNode[$nodeName])){
				if(empty($vars)){ //final node
					unset($currentNode[$nodeName]);
				}elseif(is_array($currentNode[$nodeName])){
					$currentNode =& $currentNode[$nodeName];
				}
			}else{
				break;
			}
		}
	}

	/**
	 * @param       $k
	 * @param mixed $default
	 *
	 * @return bool|mixed
	 */
	public function get($k, $default = false){
		return ($this->correct and isset($this->config[$k])) ? $this->config[$k] : $default;
	}

	/**
	 * @param string $k key to be set
	 * @param mixed  $v value to set key
	 */
	public function set($k, $v = true){
		$this->config[$k] = $v;
		$this->changed = true;
		foreach($this->nestedCache as $nestedKey => $nvalue){
			if(substr($nestedKey, 0, strlen($k) + 1) === ($k . ".")){
				unset($this->nestedCache[$nestedKey]);
			}
		}
	}

	/**
	 * @param array $v
	 */
	public function setAll($v){
		$this->config = $v;
		$this->changed = true;
	}

	/**
	 * @param      $k
	 * @param bool $lowercase If set, searches Config in single-case / lowercase.
	 *
	 * @return bool
	 */
	public function exists($k, $lowercase = false){
		if($lowercase === true){
			$k = strtolower($k); //Convert the requested key to a lower one
			$array = array_change_key_case($this->config, CASE_LOWER); //Change all keys in the array to a lower value
			return isset($array[$k]); //Find $k in the modified array
		}else{
			return isset($this->config[$k]);
		}
	}

	/**
	 * @param string $k
	 *
	 * @return void
	 */
	public function remove($k){
		unset($this->config[$k]);
		$this->changed = true;
	}

	/**
	 * @return array
	 * @phpstan-return list<string>|array<string, mixed>
	 */
	public function getAll(bool $keys = false) : array{
		return ($keys ? array_keys($this->config) : $this->config);
	}

	/**
	 * @param array $defaults
	 */
	public function setDefaults(array $defaults){
		$this->fillDefaults($defaults, $this->config);
	}

	/**
	 * @param $default
	 * @param $data
	 *
	 * @return int
	 */
	private function fillDefaults($default, &$data){
		$changed = 0;
		foreach($default as $k => $v){
			if(is_array($v)){
				if(!isset($data[$k]) or !is_array($data[$k])){
					$data[$k] = [];
				}
				$changed += $this->fillDefaults($v, $data[$k]);
			}elseif(!isset($data[$k])){
				$data[$k] = $v;
				++$changed;
			}
		}

		if($changed > 0){
			$this->changed = true;
		}

		return $changed;
	}

	/**
	 * @param $content
	 */
	private function parseList($content){
		foreach(explode("\n", trim(str_replace("\r\n", "\n", $content))) as $v){
			$v = trim($v);
			if($v == ""){
				continue;
			}
			$this->config[$v] = true;
		}
	}

	/**
	 * @return string
	 */
	private function writeProperties(){
		$content = "#Properties Config file\r\n#" . date("D M j H:i:s T Y") . "\r\n";
		foreach($this->config as $k => $v){
			if(is_bool($v) === true){
				$v = $v === true ? "on" : "off";
			}elseif(is_array($v)){
				$v = implode(";", $v);
			}
			$content .= $k . "=" . $v . "\r\n";
		}

		return $content;
	}

	/**
	 * @param $content
	 */
	private function parseProperties($content){
		if(preg_match_all('/([a-zA-Z0-9\-_\.]*)=([^\r\n]*)/u', $content, $matches) > 0){ //false or 0 matches
			foreach($matches[1] as $i => $k){
				$v = trim($matches[2][$i]);
				switch(strtolower($v)){
					case "on":
					case "true":
					case "yes":
						$v = true;
						break;
					case "off":
					case "false":
					case "no":
						$v = false;
						break;
				}
				if(isset($this->config[$k])){
					MainLogger::getLogger()->debug("[Config] Repeating property " . $k . " in the file " . $this->file);
				}
				$this->config[$k] = $v;
			}
		}
	}

}
