<?php



namespace pocketmine\plugin;

use pocketmine\utils\MainLogger;
use pocketmine\event\plugin\PluginDisableEvent;
use pocketmine\event\plugin\PluginEnableEvent;
use pocketmine\Server;

/**
 * Works with different types of plugins
 */
class PharPluginLoader implements PluginLoader {

    /** @var Server */
    private $server;

    /**
     * @param Server $server
     */
    public function __construct(Server $server){
        $this->server = $server;
    }

    /**
     * Loads the plugin contained in $file
     *
     * @param string $file
     *
     * @return Plugin
     *
     * @throws \Throwable
     */
    public function loadPlugin($file){
        if(($description = $this->getPluginDescription($file)) instanceof PluginDescription){
            MainLogger::getLogger()->info('§fLoading §b' .$description->getFullName());
            $dataFolder = dirname($file) . DIRECTORY_SEPARATOR . $description->getName();
            if(file_exists($dataFolder) and !is_dir($dataFolder)){
                throw new \InvalidStateException("The predicted data folder '" . $dataFolder . "' for " . $description->getName() . " exists and is not a directory");
            }
            $file = "phar://$file";
            $className = $description->getMain();
            $this->server->getLoader()->addPath("$file/src");

            if(class_exists($className, true)){
                $plugin = new $className();
                $this->initPlugin($plugin, $description, $dataFolder, $file);

                return $plugin;
            }else{
                throw new PluginException("Failed to load plugin " . $description->getName() . ": main class not found");
            }
        }

        return null;
    }

    /**
     * Gets the plugin description from the file
     *
     * @param string $file
     *
     * @return PluginDescription
     */
    public function getPluginDescription($file){
        $phar = new \Phar($file);
        if(isset($phar["plugin.yml"])){
            $pluginYml = $phar["plugin.yml"];
            if($pluginYml instanceof \PharFileInfo){
                return new PluginDescription($pluginYml->getContent());
            }
        }

        return null;
    }

    /**
     * Returns the file name patterns accepted by this loader.
     *
     * @return string
     */
    public function getPluginFilters(){
        return "/\\.phar$/i";
    }

    public function canLoadPlugin(string $path) : bool{
        $ext = ".phar";
        return is_file($path) and substr($path, -strlen($ext)) === $ext;
    }

    /**
     * @param PluginBase        $plugin
     * @param PluginDescription $description
     * @param string            $dataFolder
     * @param string            $file
     */
    private function initPlugin(PluginBase $plugin, PluginDescription $description, $dataFolder, $file){
        $plugin->init($this, $this->server, $description, $dataFolder, $file);
        $plugin->onLoad();
    }

    /**
     * @param Plugin $plugin
     */
    public function enablePlugin(Plugin $plugin){
        if($plugin instanceof PluginBase and !$plugin->isEnabled()){
            $this->server->getLogger()->info($this->server->getLanguage()->translateString("pocketmine.plugin.enable", [$plugin->getDescription()->getFullName()]));

            $plugin->setEnabled();

            $this->server->getPluginManager()->callEvent(new PluginEnableEvent($plugin));
        }
    }

    /**
     * @param Plugin $plugin
     */
    public function disablePlugin(Plugin $plugin){
        if($plugin instanceof PluginBase and $plugin->isEnabled()){
            $this->server->getLogger()->info($this->server->getLanguage()->translateString("pocketmine.plugin.disable", [$plugin->getDescription()->getFullName()]));

            $this->server->getPluginManager()->callEvent(new PluginDisableEvent($plugin));

            $plugin->setEnabled(false);
        }
    }
}
