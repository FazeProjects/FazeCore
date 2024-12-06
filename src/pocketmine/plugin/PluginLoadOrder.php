<?php


 
namespace pocketmine\plugin;


abstract class PluginLoadOrder {
	/*
	 * The plugin will be loaded on startup.
	 */
	const STARTUP = 0;

	/*
	 * The plugin will be loaded after the first world is loaded/created.
	 */
	const POSTWORLD = 1;
}