<?php


 
namespace pocketmine\plugin;

/**
 * Works with different types of plugins
 */
interface PluginLoader {

    /**
     * Loads the plugin contained in $file
     *
     * @param string $file
     *
     * @return Plugin
     */
    public function loadPlugin($file);

    /**
     * Gets the plugin description from the file
     *
     * @param string $file
     *
     * @return PluginDescription
     */
    public function getPluginDescription($file);

    /**
     * Returns the regular expression patterns for file names accepted by this loader.
     *
     * @return string
     */
    public function getPluginFilters();

    /**
     * Returns whether this PluginLoader can load a plugin from the given path.
     *
     * @param string $path
     *
     * @return bool
     */
    public function canLoadPlugin(string $path) : bool;

    /**
     * @param Plugin $plugin
     *
     * @return void
     */
    public function enablePlugin(Plugin $plugin);

    /**
     * @param Plugin $plugin
     *
     * @return void
     */
    public function disablePlugin(Plugin $plugin);
}
