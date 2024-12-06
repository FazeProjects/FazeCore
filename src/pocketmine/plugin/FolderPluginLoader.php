<?php



namespace pocketmine\plugin;

use pocketmine\Server;


use pocketmine\event\plugin\PluginDisableEvent;
use pocketmine\event\plugin\PluginEnableEvent;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;


class FolderPluginLoader implements PluginLoader {

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
     */
    public function loadPlugin($file){
        if(is_dir($file) and file_exists($file . "/plugin.yml") and file_exists($file . "/src/")){
            if(($description = $this->getPluginDescription($file)) instanceof PluginDescription){
                MainLogger::getLogger()->info('§fLoading §b' .$description->getFullName());
                $dataFolder = dirname($file) . DIRECTORY_SEPARATOR . $description->getName();
                if(file_exists($dataFolder) and !is_dir($dataFolder)){
                    trigger_error("The predicted data folder '" . $dataFolder . "' for " . $description->getName() . " exists and is not a directory", E_USER_WARNING);

                    return null;
                }


                $className = $description->getMain();
                $this->server->getLoader()->addPath($file . "/src");

                if(class_exists($className, true)){
                    $plugin = new $className();
                    $this->initPlugin($plugin, $description, $dataFolder, $file);

                    return $plugin;
                }else{
                    trigger_error("Failed to load source plugin " . $description->getName() . ": main class not found", E_USER_WARNING);

                    return null;
                }
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
        if(is_dir($file) and file_exists($file . "/plugin.yml")){
            $yaml = @file_get_contents($file . "/plugin.yml");
            if($yaml != ""){
                return new PluginDescription($yaml);
            }
        }

        return null;
    }

    /**
     * Returns the file name patterns accepted by this loader.
     *
     * @return array|string
     */
    public function getPluginFilters(){
        return "/[^\\.]/";
    }

    public function canLoadPlugin(string $path) : bool{
        return is_dir($path) and file_exists($path . "/plugin.yml") and file_exists($path . "/src/");
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
            $plugin->setEnabled();

            Server::getInstance()->getPluginManager()->callEvent(new PluginEnableEvent($plugin));
        }
    }

    /**
     * @param Plugin $plugin
     */
    public function disablePlugin(Plugin $plugin){
        if($plugin instanceof PluginBase and $plugin->isEnabled()){
            Server::getInstance()->getPluginManager()->callEvent(new PluginDisableEvent($plugin));

            $plugin->setEnabled(false);
        }
    }
}
