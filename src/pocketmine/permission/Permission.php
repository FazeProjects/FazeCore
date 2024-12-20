<?php



/**
 * Permission Related Classes
 */
namespace pocketmine\permission;

use pocketmine\Server;

/**
 * Represents resolution
 */
class Permission {
	const DEFAULT_OP = "op";
	const DEFAULT_NOT_OP = "notop";
	const DEFAULT_TRUE = "true";
	const DEFAULT_FALSE = "false";

	public static $DEFAULT_PERMISSION = self::DEFAULT_OP;

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public static function getByName($value){
		if(is_bool($value)){
			if($value === true){
				return "true";
			}else{
				return "false";
			}
		}
		switch(strtolower($value)){
			case "op":
			case "isop":
			case "operator":
			case "isoperator":
			case "admin":
			case "isadmin":
				return self::DEFAULT_OP;

			case "!op":
			case "notop":
			case "!operator":
			case "notoperator":
			case "!admin":
			case "notadmin":
				return self::DEFAULT_NOT_OP;

			case "true":
				return self::DEFAULT_TRUE;

			default:
				return self::DEFAULT_FALSE;
		}
	}

	/** @var string */
	private $name;

	/** @var string */
	private $description;

	/**
	 * @var string[]
	 */
	private $children = [];

	/** @var string */
	private $defaultValue;

    /**
     * Creates a new Permission object that will be attached to Permission objects.
     *
	 * @param string       $name
	 * @param string       $description
	 * @param string       $defaultValue
	 * @param Permission[] $children
	 */
	public function __construct($name, $description = null, $defaultValue = null, array $children = []){
		$this->name = $name;
		$this->description = $description !== null ? $description : "";
		$this->defaultValue = $defaultValue !== null ? $defaultValue : self::$DEFAULT_PERMISSION;
		$this->children = $children;

		$this->recalculatePermissibles();
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function &getChildren(){
		return $this->children;
	}

	/**
	 * @return string
	 */
	public function getDefault(){
		return $this->defaultValue;
	}

	/**
	 * @param string $value
	 */
	public function setDefault($value){
		if($value !== $this->defaultValue){
			$this->defaultValue = $value;
			$this->recalculatePermissibles();
		}
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @param string $value
	 */
	public function setDescription($value){
		$this->description = $value;
	}

	/**
	 * @return Permissible[]
	 */
	public function getPermissibles(){
		return Server::getInstance()->getPluginManager()->getPermissionSubscriptions($this->name);
	}

	public function recalculatePermissibles(){
		$perms = $this->getPermissibles();

		Server::getInstance()->getPluginManager()->recalculatePermissionDefaults($this);

		foreach($perms as $p){
			$p->recalculatePermissions();
		}
	}


	/**
	 * @param string|Permission $name
	 * @param                   $value
	 *
	 * @return Permission|null Permission if $name is a string, void if it's a Permission
	 */
	public function addParent($name, $value){
		if($name instanceof Permission){
			$name->getChildren()[$this->getName()] = $value;
			$name->recalculatePermissibles();
			return null;
		}else{
			$perm = Server::getInstance()->getPluginManager()->getPermission($name);
			if($perm === null){
				$perm = new Permission($name);
				Server::getInstance()->getPluginManager()->addPermission($perm);
			}

			$this->addParent($perm, $value);

			return $perm;
		}
	}

	/**
	 * @param array $data
	 * @param       $default
	 *
	 * @return Permission[]
	 */
	public static function loadPermissions(array $data, $default = self::DEFAULT_OP){
		$result = [];
		foreach($data as $key => $entry){
            try {
                $result[] = self::loadPermission($key, $entry, $default, $result);
            } catch (\Throwable $e) {
            }
        }

		return $result;
	}

	/**
	 * @param string $name
	 * @param array  $data
	 * @param string $default
	 * @param array  $output
	 *
	 * @return Permission
	 *
	 * @throws \Throwable
	 */
	public static function loadPermission($name, array $data, $default = self::DEFAULT_OP, &$output = []){
		$desc = null;
		$children = [];
		if(isset($data["default"])){
			$value = Permission::getByName($data["default"]);
			if($value !== null){
				$default = $value;
			}else{
				throw new \InvalidStateException("The 'default' key contains an unknown value");
			}
		}

		if(isset($data["children"])){
			if(is_array($data["children"])){
				foreach($data["children"] as $k => $v){
					if(is_array($v)){
						if(($perm = self::loadPermission($k, $v, $default, $output)) !== null){
							$output[] = $perm;
						}
					}
					$children[$k] = true;
				}
			}else{
				throw new \InvalidStateException("Key 'children' is of invalid type");
			}
		}

		if(isset($data["description"])){
			$desc = $data["description"];
		}

		return new Permission($name, $desc, $default, $children);

	}


}