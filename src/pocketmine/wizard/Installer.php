<?php



namespace pocketmine\wizard;

use pocketmine\utils\Config;
use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;

class Installer {
	const DEFAULT_NAME = "FazeMC";
	const DEFAULT_PORT = 19132;
	const DEFAULT_PLAYERS = 20;
	const DEFAULT_GAMEMODE = 0;
	const DEFAULT_LEVEL_NAME = "world";
	const DEFAULT_LEVEL_TYPE = "DEFAULT";

	const LEVEL_TYPES = [
		"DEFAULT",
		"FLAT",
		"NORMAL",
		"NORMAL2",
		"HELL",
		"VOID"
	];

	private $defaultLang, $lang;

	public function __construct(){

	}

	public function run(){
		echo "[•] FazeCore Installer\n";
		echo "[•] Please select a language:\n";
		foreach(InstallerLang::$languages as $short => $native){
			echo " $native => $short\n";
		}
		do{
			echo "[?] Language (eng): ";
			$lang = strtolower($this->getInput("eng"));
			if(!isset(InstallerLang::$languages[$lang])){
				echo "[!] Language not found\n";
				$lang = false;
			}
			$this->defaultLang = $lang;
		}while($lang == false);
		$this->lang = new InstallerLang($lang);


		echo "[*] " . $this->lang->get("language_has_been_selected") . "\n";

		$this->relayLangSetting();

		if(!$this->showLicense()){
			return false;
		}

		echo "[?] " . $this->lang->get("skip_installer") . " (y/N): ";
		if(strtolower($this->getInput()) === "y"){
			return true;
		}
		echo "\n";
		$this->welcome();
		$this->generateBaseConfig();
		$this->generateUserFiles();

		$this->networkFunctions();

		$this->endWizard();
		return true;
	}

	public function getDefaultLang(){
		return $this->defaultLang;
	}

	/**
	 * @return bool
	 */
	private function showLicense(){
		echo $this->lang->welcome_to_pocketmine . "\n";
		echo <<<LICENSE

  This program is paid software and may not be redistributed or modified
  under the terms of the FazeCore GNU Lesser Public License as published
  Free Software Foundation, or version 3 of the License, or
  (your choice) any later version.
  
LICENSE;
		echo "\n[?] " . $this->lang->accept_license . " (y/N): ";
		if(strtolower($this->getInput("n")) != "y"){
			echo "[!] " . $this->lang->you_have_to_accept_the_license . "\n";
			sleep(5);

			return false;
		}

		return true;
	}

	private function welcome(){
		echo "[*] " . $this->lang->setting_up_server_now . "\n";
		echo "[*] " . $this->lang->default_values_info . "\n";
		echo "[*] " . $this->lang->server_properties . "\n";

	}

	private function generateBaseConfig(){
		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);
		echo "[?] " . $this->lang->name_your_server . " (" . self::DEFAULT_NAME . "): ";
		$server_name = $this->getInput(self::DEFAULT_NAME);
		$config->set("server-name", $server_name);
		$config->set("motd", $server_name);
		echo "[*] " . $this->lang->port_warning . "\n";
		do{
			echo "[?] " . $this->lang->server_port . " (" . self::DEFAULT_PORT . "): ";
			$port = (int) $this->getInput(self::DEFAULT_PORT);
			if($port <= 0 or $port > 65535){
				echo "[!] " . $this->lang->invalid_port . "\n";
			}
		}while($port <= 0 or $port > 65535);
		$config->set("server-port", $port);

		echo "[*] " . $this->lang->online_mode_info . "\n";
		echo "[?] " . $this->lang->online_mode . " (y/N): ";
		$config->set("online-mode", strtolower($this->getInput("y")) == "y");

		echo "[?] " . $this->lang->level_name . " (" . self::DEFAULT_LEVEL_NAME . "): ";
		$config->set("level-name", $this->getInput(self::DEFAULT_LEVEL_NAME));

		do{
			echo "[?] " . $this->lang->level_type . " (" . self::DEFAULT_LEVEL_TYPE . "): ";
			$type = strtoupper($this->getInput(self::DEFAULT_LEVEL_TYPE));
			if(!in_array($type, self::LEVEL_TYPES)){
				echo "[!] " . $this->lang->invalid_level_type . "\n";
			}
		}while(!in_array($type, self::LEVEL_TYPES));
		$config->set("level-type", $type);

		echo "[*] " . $this->lang->gamemode_info . "\n";
		do{
			echo "[?] " . $this->lang->default_gamemode . ": (" . self::DEFAULT_GAMEMODE . "): ";
			$gamemode = (int) $this->getInput(self::DEFAULT_GAMEMODE);
		}while($gamemode < 0 or $gamemode > 3);
		$config->set("gamemode", $gamemode);
		echo "[?] " . $this->lang->max_players . " (" . self::DEFAULT_PLAYERS . "): ";
		$config->set("max-players", (int) $this->getInput(self::DEFAULT_PLAYERS));
		echo "[*] " . $this->lang->spawn_protection_info . "\n";
		echo "[?] " . $this->lang->spawn_protection . " (Y/n): ";
		if(strtolower($this->getInput("y")) == "n"){
			$config->set("spawn-protection", -1);
		}else{
			$config->set("spawn-protection", 16);
		}

		echo "[?] " . $this->lang->announce_player_achievements . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("announce-player-achievements", "on");
		}else{
			$config->set("announce-player-achievements", "off");
		}
		$config->save();
	}

	private function generateUserFiles(){
		echo "[*] " . $this->lang->op_info . "\n";
		echo "[?] " . $this->lang->op_who . ": ";
		$op = strtolower($this->getInput());
		if($op === ""){
			echo "[!] " . $this->lang->op_warning . "\n";
		}else{
			$ops = new Config(\pocketmine\DATA . "ops.txt", Config::ENUM);
			$ops->set($op, true);
			$ops->save();
		}
		echo "[*] " . $this->lang->whitelist_info . "\n";
		echo "[?] " . $this->lang->whitelist_enable . " (y/N): ";
		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);
		if(strtolower($this->getInput("n")) === "y"){
			echo "[!] " . $this->lang->whitelist_warning . "\n";
			$config->set("white-list", true);
		}else{
			$config->set("white-list", false);
		}
		$config->save();
	}

	private function networkFunctions(){
		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);
		echo "[!] " . $this->lang->query_warning1 . "\n";
		echo "[!] " . $this->lang->query_warning2 . "\n";
		echo "[?] " . $this->lang->query_disable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("enable-query", false);
		}else{
			$config->set("enable-query", true);
		}

		echo "[*] " . $this->lang->rcon_info . "\n";
		echo "[?] " . $this->lang->rcon_enable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("enable-rcon", true);
            try {
                $password = substr(base64_encode(random_bytes(20)), 3, 10);
            } catch (\Exception $e) {
            }
            $config->set("rcon.password", $password);
			echo "[*] " . $this->lang->rcon_password . ": " . $password . "\n";
		}else{
			$config->set("enable-rcon", false);
		}

		/*echo "[*] " . $this->lang->usage_info . "\n";
		echo "[?] " . $this->lang->usage_disable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("send-usage", false);
		}else{
			$config->set("send-usage", true);
		}*/
		$config->save();


		echo "[*] " . $this->lang->ip_get . "\n";

		$externalIP = Internet::getIP();
		if($externalIP === false){
			$externalIP = "unknown (server offline)";
		}

		try{
			$internalIP = Internet::getInternalIP();
		}catch(InternetException $e){
			$internalIP = "unknown (" . $e->getMessage() . ")";
		}

		echo "[!] " . $this->lang->get("ip_warning", ["{{EXTERNAL_IP}}", "{{INTERNAL_IP}}"], [$externalIP, $internalIP]) . "\n";
		echo "[!] " . $this->lang->ip_confirm;
		$this->getInput();
	}

	private function relayLangSetting(){
		if(file_exists(\pocketmine\DATA . "lang.txt")){
			unlink(\pocketmine\DATA . "lang.txt");
		}
		$langFile = new Config(\pocketmine\DATA . "lang.txt", Config::ENUM);
		$langFile->set($this->defaultLang, true);
		$langFile->save();
	}

	private function endWizard(){
		echo "[*] " . $this->lang->you_have_finished . "\n";
		echo "[*] " . $this->lang->pocketmine_plugins . "\n";
		echo "[*] " . $this->lang->pocketmine_will_start . "\n\n\n";
		sleep(4);
	}

	/**
	 * @param string $default
	 *
	 * @return string
	 */
	private function getInput($default = ""){
		$input = trim(fgets(STDIN));

		return $input === "" ? $default : $input;
	}
}