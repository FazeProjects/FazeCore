<?php



namespace pocketmine\plugin;

use pocketmine\command\PluginCommand;
use pocketmine\command\SimpleCommandMap;
use pocketmine\event\Event;
use pocketmine\event\EventPriority;
use pocketmine\event\HandlerList;
use pocketmine\event\Listener;
use pocketmine\timings\Timings;
use pocketmine\timings\TimingsHandler;
use pocketmine\permission\Permissible;
use pocketmine\permission\Permission;
use pocketmine\Server;
use pocketmine\utils\Utils;
use function mb_strtoupper;

/**
 * Manages all plugins, permissions and permissions
 */
class PluginManager{
	private const MAX_EVENT_CALL_DEPTH = 1000; // maximum number of events

	/** @var Server */
	private $server;

	/** @var SimpleCommandMap */
	private $commandMap;

	/**
	 * @var Plugin[]
	 */
	protected $plugins = [];

	/**
	 * @var Permission[]
	 */
	protected $permissions = [];

	/**
	 * @var Permission[]
	 */
	protected $defaultPerms = [];

	/**
	 * @var Permission[]
	 */
	protected $defaultPermsOp = [];

	/**
	 * @var Permissible[][]
	 */
	protected $permSubs = [];

	/**
	 * @var Permissible[]
	 */
	protected $defSubs = [];

	/**
	 * @var Permissible[]
	 */
	protected $defSubsOp = [];

	/**
	 * @var PluginLoader[]
	 */
	protected $fileAssociations = [];

	/** @var int */
	private $eventCallDepth = 0;

	/**
	 * @param Server           $server
	 * @param SimpleCommandMap $commandMap
	 */
	public function __construct(Server $server, SimpleCommandMap $commandMap){
		$this->server = $server;
		$this->commandMap = $commandMap;
	}

	/**
	 * @param string $name
	 *
	 * @return null|Plugin
	 */
	public function getPlugin($name){
		if(isset($this->plugins[$name])){
			return $this->plugins[$name];
		}

		return null;
	}

	/**
	 * @param string $loaderName A PluginLoader class name
	 *
	 * @return bool
	 */
	public function registerInterface($loaderName){
		if(is_subclass_of($loaderName, PluginLoader::class)){
			$loader = new $loaderName($this->server);
		}else{
			return false;
		}

		$this->fileAssociations[$loaderName] = $loader;

		return true;
	}

	/**
	 * @return Plugin[]
	 */
	public function getPlugins(){
		return $this->plugins;
	}

	/**
	 * @param string         $path
	 * @param PluginLoader[] $loaders
	 *
	 * @return Plugin
	 */
	public function loadPlugin($path, $loaders = null){
		foreach(($loaders === null ? $this->fileAssociations : $loaders) as $loader){
			if(preg_match($loader->getPluginFilters(), basename($path)) > 0){
				$description = $loader->getPluginDescription($path);
				if($description instanceof PluginDescription){
					if(($plugin = $loader->loadPlugin($path)) instanceof Plugin){
						$this->plugins[$plugin->getDescription()->getName()] = $plugin;

						$pluginCommands = $this->parseYamlCommands($plugin);

						if(count($pluginCommands) > 0){
							$this->commandMap->registerAll($plugin->getDescription()->getName(), $pluginCommands);
						}

						return $plugin;
					}
				}
			}
		}

		return null;
	}

	/**
	 * @param string $directory
	 * @param array  $newLoaders
	 *
	 * @return Plugin[]
	 */
	public function loadPlugins($directory, $newLoaders = null){
		if(is_dir($directory)){
			$plugins = [];
			$loadedPlugins = [];
			$dependencies = [];
			$softDependencies = [];
			if(is_array($newLoaders)){
				$loaders = [];
				foreach($newLoaders as $key){
					if(isset($this->fileAssociations[$key])){
						$loaders[$key] = $this->fileAssociations[$key];
					}
				}
			}else{
				$loaders = $this->fileAssociations;
			}
			foreach($loaders as $loader){
				foreach(new \RegexIterator(new \DirectoryIterator($directory), $loader->getPluginFilters()) as $file){
					if($file === "." or $file === ".."){
						continue;
					}
					$file = $directory . $file;
					if(!$loader->canLoadPlugin($file)){
						continue;
					}
					try{
						$description = $loader->getPluginDescription($file);
						if($description instanceof PluginDescription){
							$name = $description->getName();
							if(stripos($name, "pocketmine") !== false or stripos($name, "minecraft") !== false or stripos($name, "mojang") !== false){
								$this->server->getLogger()->error($this->server->getLanguage()->translateString("pocketmine.plugin.loadError", [$name, "%pocketmine.plugin.restrictedName"]));
								continue;
							}elseif(strpos($name, " ") !== false){
								$this->server->getLogger()->warning($this->server->getLanguage()->translateString("pocketmine.plugin.spacesDiscouraged", [$name]));
							}

							if(isset($plugins[$name]) or $this->getPlugin($name) instanceof Plugin){
								$this->server->getLogger()->error($this->server->getLanguage()->translateString("pocketmine.plugin.duplicateError", [$name]));
								continue;
							}

							$compatibleExtraApi = false;
							foreach($description->getCompatibleExtraApis() as $version){
								//Format: majorVersion.minorVersion.patch
								$version = array_map("intval", explode(".", $version));
								$apiVersion = array_map("intval", explode(".", $this->server->getExtraApiVersion()));
								//Completely different API version
								if($version[0] > $apiVersion[0]){
									continue;
								}
								//If the plugin uses new API
								if($version[0] < $apiVersion[0]){
									$compatibleExtraApi = true;
									break;
								}
								//If the plugin requires new API features, being backwards compatible
								if($version[1] > $apiVersion[1]){
									continue;
								}

								if($version[1] == $apiVersion[1] and $version[2] > $apiVersion[2]){
									continue;
								}

								$compatibleExtraApi = true;
								break;
							}

							if(!$this->isCompatibleApi(...$description->getCompatibleApis())){
								if($this->server->loadIncompatibleAPI === true){
                                    $this->server->getLogger()->debug("The API of plugin {$name} does not match the server, but FazeCore loaded it anyway");
								}else{
									$this->server->getLogger()->error($this->server->getLanguage()->translateString("pocketmine.plugin.loadError", [$name, "%pocketmine.plugin.incompatibleAPI"]));
									continue;
								}
							}

							if($compatibleExtraApi === false){
                                $this->server->getLogger()->error("Unable to load plugin '{$description->getName()}': Incompatible API version");
								continue;
							}

							$plugins[$name] = $file;

							$softDependencies[$name] = array_merge($softDependencies[$name] ?? [], $description->getSoftDepend());
							$dependencies[$name] = $description->getDepend();

							foreach($description->getLoadBefore() as $before){
								if(isset($softDependencies[$before])){
									$softDependencies[$before][] = $name;
								}else{
									$softDependencies[$before] = [$name];
								}
							}
						}
					}catch(\Throwable $e){
						$this->server->getLogger()->error($this->server->getLanguage()->translateString("pocketmine.plugin.fileError", [$file, $directory, $e->getMessage()]));
						$this->server->getLogger()->logException($e);
					}
				}
			}


			while(count($plugins) > 0){
				$loadedThisLoop = 0;
				foreach($plugins as $name => $file){
					if(isset($dependencies[$name])){
						foreach($dependencies[$name] as $key => $dependency){
							if(isset($loadedPlugins[$dependency]) or $this->getPlugin($dependency) instanceof Plugin){
								unset($dependencies[$name][$key]);
							}elseif(!isset($plugins[$dependency])){
								$this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.plugin.loadError", [$name, "%pocketmine.plugin.unknownDependency"]));
								unset($plugins[$name]);
								continue 2;
							}
						}

						if(count($dependencies[$name]) === 0){
							unset($dependencies[$name]);
						}
					}

					if(isset($softDependencies[$name])){
						foreach($softDependencies[$name] as $key => $dependency){
                            if(isset($loadedPlugins[$dependency]) or $this->getPlugin($dependency) instanceof Plugin){
                                $this->server->getLogger()->debug("Successfully resolved soft dependency \"$dependency\" for plugin \"$name\"");
                                unset($softDependencies[$name][$key]);
                            }elseif(!isset($plugins[$dependency])){
                                                            //this dependency will never be resolved, so don't even try
                                $this->server->getLogger()->debug("Skipping resolution of missing soft dependency \"$dependency\" for plugin \"$name\"");
                                unset($softDependencies[$name][$key]);
                            }else{
                                $this->server->getLogger()->debug("Delaying resolution of soft dependency \"$dependency\" for plugin \"$name\" (found but not yet loaded)");
                            }
						}

						if(count($softDependencies[$name]) === 0){
							unset($softDependencies[$name]);
						}
					}

					if(!isset($dependencies[$name]) and !isset($softDependencies[$name])){
						unset($plugins[$name]);
						$loadedThisLoop++;
					    if(($plugin = $this->loadPlugin($file, $loaders)) instanceof Plugin){
							$loadedPlugins[$name] = $plugin;
						}else{
							$this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.plugin.genericLoadError", [$name]));
						}
					}
				}

				if($loadedThisLoop === 0){
					//No plugins loaded :(
					foreach($plugins as $name => $file){
					    $this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.plugin.loadError", [$name, "%pocketmine.plugin.circularDependency"]));
					}
					$plugins = [];
				}
			}

			return $loadedPlugins;
		}else{
			return [];
		}
	}

    /**
     * Returns whether the specified API version string is considered compatible with the server API version.
     *
     * @param string ...$versions
     * @return bool
     */
    public function isCompatibleApi(string ...$versions) : bool{
        $serverString = $this->server->getApiVersion();
        $serverApi = array_pad(explode("-", $serverString, 2), 2, "");
        $serverNumbers = array_map("intval", explode(".", $serverApi[0]));

        foreach($versions as $version){
            // Format: majorVersion.minorVersion.patch (3.0.0)
            //    or: majorVersion.minorVersion.patch-devBuild (3.0.0-alpha1)
            if($version !== $serverString){
                $pluginApi = array_pad(explode("-", $version, 2), 2, ""); // 0 = version, 1 = suffix (optional)

                // Check if the release phase (alpha vs beta) or build phase (alpha.1 vs alpha.2) differs
                if(mb_strtoupper($pluginApi[1]) !== mb_strtoupper($serverApi[1])){
                    continue;
                }

                $pluginNumbers = array_map("intval", array_pad(explode(".", $pluginApi[0]), 3, "0")); // Plugins might specify API versions like "3.0" or "3"

                // Check if the major version of the plugin is different from the server
                if($pluginNumbers[0] !== $serverNumbers[0]){
                    continue;
                }

                // If the plugin requires new API features, it must be compatible with older versions
                if($pluginNumbers[1] > $serverNumbers[1]){
                    continue;
                }

                // If the plugin requires bug fixes in the patch versions, it must be backwards compatible
                if($pluginNumbers[2] > $serverNumbers[2]){
                    continue;
                }
            }

            // If all checks pass, the plugin is compatible with the server API version
            return true;
        }

        // Return false if none of the versions matched
        return false;
    }

    /**
	 * @param string $name
	 *
	 * @return null|Permission
	 */
	public function getPermission($name){
		return $this->permissions[$name] ?? null;
	}

	/**
	 * @param Permission $permission
	 *
	 * @return bool
	 */
	public function addPermission(Permission $permission){
		if(!isset($this->permissions[$permission->getName()])){
			$this->permissions[$permission->getName()] = $permission;
			$this->calculatePermissionDefault($permission);

			return true;
		}

		return false;
	}

	/**
	 * @param string|Permission $permission
	 */
	public function removePermission($permission){
		if($permission instanceof Permission){
			unset($this->permissions[$permission->getName()]);
		}else{
			unset($this->permissions[$permission]);
		}
	}

	/**
	 * @param bool $op
	 *
	 * @return Permission[]
	 */
	public function getDefaultPermissions($op){
		if($op === true){
			return $this->defaultPermsOp;
		}else{
			return $this->defaultPerms;
		}
	}

	/**
	 * @param Permission $permission
	 */
	public function recalculatePermissionDefaults(Permission $permission){
		if(isset($this->permissions[$permission->getName()])){
			unset($this->defaultPermsOp[$permission->getName()]);
			unset($this->defaultPerms[$permission->getName()]);
			$this->calculatePermissionDefault($permission);
		}
	}

	/**
	 * @param Permission $permission
	 */
	private function calculatePermissionDefault(Permission $permission){
		Timings::$permissionDefaultTimer->startTiming();
		if($permission->getDefault() === Permission::DEFAULT_OP or $permission->getDefault() === Permission::DEFAULT_TRUE){
			$this->defaultPermsOp[$permission->getName()] = $permission;
			$this->dirtyPermissibles(true);
		}

		if($permission->getDefault() === Permission::DEFAULT_NOT_OP or $permission->getDefault() === Permission::DEFAULT_TRUE){
			$this->defaultPerms[$permission->getName()] = $permission;
			$this->dirtyPermissibles(false);
		}
		Timings::$permissionDefaultTimer->stopTiming();
	}

	/**
	 * @param bool $op
	 */
	private function dirtyPermissibles($op){
		foreach($this->getDefaultPermSubscriptions($op) as $p){
			$p->recalculatePermissions();
		}
	}

	/**
	 * @param string      $permission
	 * @param Permissible $permissible
	 */
	public function subscribeToPermission($permission, Permissible $permissible){
		if(!isset($this->permSubs[$permission])){
			$this->permSubs[$permission] = [];
		}
		$this->permSubs[$permission][spl_object_hash($permissible)] = $permissible;
	}

	/**
	 * @param string      $permission
	 * @param Permissible $permissible
	 */
	public function unsubscribeFromPermission($permission, Permissible $permissible){
		if(isset($this->permSubs[$permission])){
			unset($this->permSubs[$permission][spl_object_hash($permissible)]);
			if(count($this->permSubs[$permission]) === 0){
				unset($this->permSubs[$permission]);
			}
		}
	}

	/**
	 * @param Permissible $permissible
	 */
	public function unsubscribeFromAllPermissions(Permissible $permissible) : void{
		foreach($this->permSubs as $permission => &$subs){
			unset($subs[spl_object_hash($permissible)]);
			if(empty($subs)){
				unset($this->permSubs[$permission]);
			}
		}
	}

	/**
	 * @param string $permission
	 *
	 * @return Permissible[]
	 */
	public function getPermissionSubscriptions($permission){
		return $this->permSubs[$permission] ?? [];
	}

	/**
	 * @param bool        $op
	 * @param Permissible $permissible
	 */
	public function subscribeToDefaultPerms($op, Permissible $permissible){
		if($op === true){
			$this->defSubsOp[spl_object_hash($permissible)] = $permissible;
		}else{
			$this->defSubs[spl_object_hash($permissible)] = $permissible;
		}
	}

	/**
	 * @param bool        $op
	 * @param Permissible $permissible
	 */
	public function unsubscribeFromDefaultPerms($op, Permissible $permissible){
		if($op === true){
			unset($this->defSubsOp[spl_object_hash($permissible)]);
		}else{
			unset($this->defSubs[spl_object_hash($permissible)]);
		}
	}

	/**
	 * @param bool $op
	 *
	 * @return Permissible[]
	 */
	public function getDefaultPermSubscriptions($op){
		if($op === true){
			return $this->defSubsOp;
		}

		return $this->defSubs;
	}

	/**
	 * @return Permission[]
	 */
	public function getPermissions(){
		return $this->permissions;
	}

	/**
	 * @param Plugin $plugin
	 *
	 * @return bool
	 */
	public function isPluginEnabled(Plugin $plugin){
		return isset($this->plugins[$plugin->getDescription()->getName()]) and $plugin->isEnabled();
	}

	/**
	 * @param Plugin $plugin
	 */
	public function enablePlugin(Plugin $plugin){
		if(!$plugin->isEnabled()){
			try{
				foreach($plugin->getDescription()->getPermissions() as $perm){
					$this->addPermission($perm);
				}
				$plugin->getPluginLoader()->enablePlugin($plugin);
			}catch(\Throwable $e){
				$this->server->getLogger()->logException($e);
				$this->disablePlugin($plugin);
			}
		}
	}

	/**
	 * @param Plugin $plugin
	 *
	 * @return PluginCommand[]
	 */
	protected function parseYamlCommands(Plugin $plugin){
		$pluginCmds = [];

		foreach($plugin->getDescription()->getCommands() as $key => $data){
			if(strpos($key, ":") !== false){
				$this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.plugin.commandError", [$key, $plugin->getDescription()->getFullName()]));
				continue;
			}
			if(is_array($data)){
				$newCmd = new PluginCommand($key, $plugin);
				if(isset($data["description"])){
					$newCmd->setDescription($data["description"]);
				}

				if(isset($data["usage"])){
					$newCmd->setUsage($data["usage"]);
				}

				if(isset($data["aliases"]) and is_array($data["aliases"])){
					$aliasList = [];
					foreach($data["aliases"] as $alias){
						if(strpos($alias, ":") !== false){
							$this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.plugin.aliasError", [$alias, $plugin->getDescription()->getFullName()]));
							continue;
						}
						$aliasList[] = $alias;
					}

					$newCmd->setAliases($aliasList);
				}

				if(isset($data["permission"])){
					$newCmd->setPermission($data["permission"]);
				}

				if(isset($data["permission-message"])){
					$newCmd->setPermissionMessage($data["permission-message"]);
				}

				$pluginCmds[] = $newCmd;
			}
		}

		return $pluginCmds;
	}

	public function disablePlugins(){
		foreach($this->getPlugins() as $plugin){
			$this->disablePlugin($plugin);
		}
	}

	/**
	 * @param Plugin $plugin
	 */
	public function disablePlugin(Plugin $plugin){
		if($plugin->isEnabled()){
			try{
				$plugin->getPluginLoader()->disablePlugin($plugin);
			}catch(\Throwable $e){
				$this->server->getLogger()->logException($e);
			}

			$this->server->getScheduler()->cancelTasks($plugin);
			HandlerList::unregisterAll($plugin);
			foreach($plugin->getDescription()->getPermissions() as $perm){
				$this->removePermission($perm);
			}
		}
	}

	public function clearPlugins(){
		$this->disablePlugins();
		$this->plugins = [];
		$this->fileAssociations = [];
		$this->permissions = [];
		$this->defaultPerms = [];
		$this->defaultPermsOp = [];
	}

	/**
	 * Calls an event
	 *
	 * @param Event $event
	 */
	public function callEvent(Event $event){
		if($this->eventCallDepth >= self::MAX_EVENT_CALL_DEPTH){
            //this exception will be caught by the parent event call if all else fails
            throw new \RuntimeException("A recursive event call was detected (maximum event call depth of " . self::MAX_EVENT_CALL_DEPTH . " events reached)");
        }

		++$this->eventCallDepth;
		foreach($event->getHandlers()->getRegisteredListeners() as $registration){
			if(!$registration->getPlugin()->isEnabled()){
				continue;
			}

			$registration->callEvent($event);
		}
		--$this->eventCallDepth;
	}

    /**
     * Registers all events in the given Listener class
     *
     * @param Listener $listener
     * @param Plugin $plugin
     *
     * @throws PluginException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function registerEvents(Listener $listener, Plugin $plugin){
        if(!$plugin->isEnabled()){
            throw new PluginException("The plugin tried to register " . get_class($listener) . " while it is not enabled");
        }

        $reflection = new \ReflectionClass(get_class($listener));
        foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            if(!$method->isStatic() and $method->getDeclaringClass()->implementsInterface(Listener::class)){
                $tags = Utils::parseDocComment((string) $method->getDocComment());
                if(isset($tags["notHandler"])){
                    continue;
                }

                $parameters = $method->getParameters();
                if(count($parameters) !== 1){
                    continue;
                }
                try{
                    $paramType = $parameters[0]->getType();
                    // isBuiltin() returns false for builtin classes ..................
                    if($paramType instanceof \ReflectionNamedType && !$paramType->isBuiltin()){
                        /** @phpstan-var class-string $paramClass */
                        $paramClass = $paramType->getName();
                        $eventClass = new \ReflectionClass($paramClass);
                    }else{
                        $eventClass = null;
                    }
                }catch(\ReflectionException $e){ // class doesn't exist
                    if(isset($tags["softDepend"]) && !isset($this->plugins[$tags["softDepend"]])){
                        $this->server->getLogger()->debug("Not registering listener @softDepend " . get_class($listener) . "::" . $method->getName() . "() because plugin \"" . $tags["softDepend"] . "\" not found");
                        continue;
                    }

                    throw $e;
                }
                if($eventClass === null or !$eventClass->isSubclassOf(Event::class)){
                    continue;
                }

                try{
                    $priority = isset($tags["priority"]) ? EventPriority::fromString($tags["priority"]) : EventPriority::NORMAL;
                }catch(\InvalidArgumentException $e){
                    throw new PluginException("Event handler " . get_class($listener) . "->" . $method->getName() . "() declares an invalid/unknown priority \"" . $tags["priority"] . "\"");
                }

                $ignoreCancelled = false;
                if(isset($tags["ignoreCancelled"])){
                    switch(strtolower($tags["ignoreCancelled"])){
                        case "true":
                        case "":
                            $ignoreCancelled = true;
                            break;
                        case "false":
                            $ignoreCancelled = false;
                            break;
                        default:
                            throw new PluginException("Event handler " . get_class($listener) . "->" . $method->getName() . "() declares an invalid @ignoreCancelled value \"" . $tags["ignoreCancelled"] . "\"");
                    }
                }

                try {
                    $this->registerEvent($eventClass->getName(), $listener, $priority, new MethodEventExecutor($method->getName()), $plugin, $ignoreCancelled);
                } catch (\ReflectionException $e) {
                } catch (\Throwable $e) {
                }
            }
        }
    }

    /**
     * @param string $event Class name that extends Event
     * @param Listener $listener
     * @param int $priority
     * @param EventExecutor $executor
     * @param Plugin $plugin
     * @param bool $ignoreCancelled
     *
     * @throws PluginException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \ReflectionException
     * @throws \ReflectionException
     */
    public function registerEvent($event, Listener $listener, $priority, EventExecutor $executor, Plugin $plugin, $ignoreCancelled = false){
        if(!is_subclass_of($event, Event::class)){
            throw new PluginException($event . " is not an event");
        }
        $class = new \ReflectionClass($event);
        if($class->isAbstract()){
            throw new PluginException($event . " is an abstract event");
        }
        if($class->getProperty("handlerList")->getDeclaringClass()->getName() !== $event){
            throw new PluginException($event . " does not have a handler list");
        }

        if(!$plugin->isEnabled()){
            throw new PluginException("The plugin tried to register " . $event . " while it is not enabled");
        }

        $timings = new TimingsHandler("Plugin: " . $plugin->getDescription()->getFullName() . " Event: " . get_class($listener) . "::" . ($executor instanceof MethodEventExecutor ? $executor->getMethod() : "???") . "(" . (new \ReflectionClass($event))->getShortName() . ")");

        $this->getEventListeners($event)->register(new RegisteredListener($listener, $executor, $priority, $plugin, $ignoreCancelled, $timings));
    }

    /**
	 * @param $event
	 *
	 * @return HandlerList
	 */
	private function getEventListeners($event){
		if($event::$handlerList === null){
			$event::$handlerList = new HandlerList();
		}

		return $event::$handlerList;
	}
}