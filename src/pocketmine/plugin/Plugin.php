<?php



/**
 * Classes related to plugins
 */

namespace pocketmine\plugin;

use pocketmine\command\CommandExecutor;


/**
 * It is recommended to use PluginBase for the actual plugin
 *
 */
interface Plugin extends CommandExecutor {

    /**
     * Called when the plugin is loaded, before onEnable() is called
     */
    public function onLoad();

    /**
     * Called when the plugin is enabled
     */
    public function onEnable();

    /**
     * @return mixed
     */
    public function isEnabled();

    /**
     * Called when the plugin is disabled
     * Use this to release open resources and finish tasks
     */
    public function onDisable();

    /**
     * @return mixed
     */
    public function isDisabled();

    /**
     * Gets the plugin's data folder for saving files and configurations
     */
    public function getDataFolder();

    /**
     * @return PluginDescription
     */
    public function getDescription();

    /**
     * Gets an embedded resource from the plugin file.
     *
     * @param string $filename
     */
    public function getResource($filename);

    /**
     * Saves an embedded resource to its relative location in the data folder
     *
     * @param string $filename
     * @param bool $replace
     */
    public function saveResource($filename, $replace = false);

    /**
     * Returns all resources packed with the plugin
     */
    public function getResources();

    /**
     * @return \pocketmine\utils\Config
     */
    public function getConfig();

    /**
     * @return mixed
     */
    public function saveConfig();

    /**
     * @return mixed
     */
    public function saveDefaultConfig();

    /**
     * @return mixed
     */
    public function reloadConfig();

    /**
     * @return \pocketmine\Server
     */
    public function getServer();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return PluginLogger
     */
    public function getLogger();

    /**
     * @return PluginLoader
     */
    public function getPluginLoader();

}
