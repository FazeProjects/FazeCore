<?php



class BaseClassLoader extends \Threaded implements ClassLoader{

	/** @var \ClassLoader */
	private $parent;
	/** @var \Threaded|string[] */
	private $lookup;
	/** @var \Threaded|string[] */
	private $classes;

	public function __construct(ClassLoader $parent = null){
		$this->parent = $parent;
		$this->lookup = new \Threaded;
		$this->classes = new \Threaded;
	}

	/**
	 * Adds a path to the lookup list
	 *
	 * @param string $path
	 * @param bool   $prepend
	 *
	 * @return void
	 */
	public function addPath($path, $prepend = false){

		foreach($this->lookup as $p){
			if($p === $path){
				return;
			}
		}

		if($prepend){
			$this->lookup->synchronized(function($path){
				$entries = $this->getAndRemoveLookupEntries();
				$this->lookup[] = $path;
				foreach($entries as $entry){
					$this->lookup[] = $entry;
				}
			}, $path);
		}else{
			$this->lookup[] = $path;
		}
	}

	/**
	 * @return string[]
	 */
	protected function getAndRemoveLookupEntries(){
		$entries = [];
		while($this->lookup->count() > 0){
			$entries[] = $this->lookup->shift();
		}
		return $entries;
	}

	/**
	 * Removes a path from the lookup list
	 *
	 * @param $path
	 */
	public function removePath($path){
		foreach($this->lookup as $i => $p){
			if($p === $path){
				unset($this->lookup[$i]);
			}
		}
	}

	/**
	 * Returns an array of the classes loaded
	 *
	 * @return string[]
	 */
	public function getClasses(){
		$classes = [];
		foreach($this->classes as $class){
			$classes[] = $class;
		}
		return $classes;
	}

	/**
	 * Returns the parent ClassLoader, if any
	 *
	 * @return ClassLoader
	 */
	public function getParent(){
		return $this->parent;
	}

	/**
	 * Attaches the ClassLoader to the PHP runtime
	 *
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function register($prepend = false){
		return spl_autoload_register(function(string $name) : void{
			$this->loadClass($name);
		}, true, $prepend);
	}

    /**
     * Called when there is a class to load
     *
     * @param string $name
     *
     * @return bool
     * @throws ReflectionException
     * @throws ReflectionException
     */
	public function loadClass($name){
		$path = $this->findClass($name);
		if($path !== null){
			include($path);
			if(!class_exists($name, false) and !interface_exists($name, false) and !trait_exists($name, false)){
				return false;
			}

            try {
                if (method_exists($name, "onClassLoaded") and (new ReflectionClass($name))->getMethod("onClassLoaded")->isStatic()) {
                    $name::onClassLoaded();
                }
            } catch (ReflectionException $e) {
            }

            $this->classes[] = $name;

			return true;
		}

		return false;
	}

	/**
	 * Returns the path for the class, if any
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public function findClass($name){
		$baseName = str_replace("\\", DIRECTORY_SEPARATOR, $name);


		foreach($this->lookup as $path){
			$filename = $path . DIRECTORY_SEPARATOR . $baseName . ".php";
			if(file_exists($filename)){
				return $filename;
			}
		}

		return null;
	}
}