<?php

namespace pocketmine\crash;

use pocketmine\Server;
use pocketmine\EssentialInfo;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginLoadOrder;
use pocketmine\plugin\PluginManager;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Utils;
use raklib\RakLib;
use function base64_encode;
use function date;
use function error_get_last;
use function file_get_contents;
use function file_exists;
use function fopen;
use function fwrite;
use function get_loaded_extensions;
use function implode;
use function is_resource;
use function json_encode;
use function json_last_error_msg;
use function max;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function php_uname;
use function phpinfo;
use function phpversion;
use function preg_replace;
use function str_split;
use function strpos;
use function substr;
use function time;
use function zend_version;
use function zlib_encode;
use const E_COMPILE_ERROR;
use const E_COMPILE_WARNING;
use const E_CORE_ERROR;
use const E_CORE_WARNING;
use const E_DEPRECATED;
use const E_ERROR;
use const E_NOTICE;
use const E_PARSE;
use const E_RECOVERABLE_ERROR;
use const E_STRICT;
use const E_USER_DEPRECATED;
use const E_USER_ERROR;
use const E_USER_NOTICE;
use const E_USER_WARNING;
use const E_WARNING;
use const FILE_IGNORE_NEW_LINES;
use const JSON_UNESCAPED_SLASHES;
use const PHP_EOL;
use const PHP_OS;

class CrashDump {

    private const FORMAT_VERSION = 2;
    private const PLUGIN_INVOLVEMENT_NONE = "none";
    private const PLUGIN_INVOLVEMENT_DIRECT = "direct";
    private const PLUGIN_INVOLVEMENT_INDIRECT = "indirect";

    /** @var Server */
    private $server;
    /** @var resource */
    private $fp;
    /** @var float */
    private $time;
    /** @var mixed[] */
    private $data = [];
    /** @var string */
    private $encodedData = "";
    /** @var string */
    private $path;

    public function __construct(Server $server) {
        $this->time = microtime(true);
        $this->server = $server;
        $this->initializeCrashDumpDirectory();
        $this->path = $this->generateCrashDumpFilePath();
        $this->fp = $this->createCrashDumpFile();
        $this->initializeCrashDumpData();
    }

    private function initializeCrashDumpDirectory(): void {
        $crashDumpDir = $this->server->getDataPath() . "crashdumps";
        if (!is_dir($crashDumpDir)) {
            mkdir($crashDumpDir);
        }
    }

    private function generateCrashDumpFilePath(): string {
        return $this->server->getCrashPath() . "CrashDump_" . date("D_M_j-H.i.s-T_Y", (int) $this->time) . ".log";
    }

    private function createCrashDumpFile() {
        $fp = @fopen($this->path, "wb");
        if (!is_resource($fp)) {
            throw new \RuntimeException("Could not create Crash Dump");
        }
        return $fp;
    }

    private function initializeCrashDumpData(): void {
        $this->data["format_version"] = self::FORMAT_VERSION;
        $this->data["time"] = $this->time;
        $this->data["uptime"] = $this->time - \pocketmine\START_TIME;
        $this->addLine($this->server->getName() . " Crash Dump " . date("D M j H:i:s T Y", (int) $this->time));
        $this->addLine();
        $this->baseCrash();
        $this->generalData();
        $this->pluginsData();
        $this->extraData();
        $this->encodeData();
        fclose($this->fp);
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getEncodedData() {
        return $this->encodedData;
    }

    public function getData(): array {
        return $this->data;
    }

    private function encodeData(): void {
        $this->addLine();
        $this->addLine("----------------------REPORT THE DATA BELOW THIS LINE-----------------------");
        $this->addLine();
        $this->addLine("===BEGIN CRASH DUMP===");

        $json = json_encode($this->data, JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException("Failed to encode crashdump JSON: " . json_last_error_msg());
        }

        $zlibEncoded = zlib_encode($json, ZLIB_ENCODING_DEFLATE, 9);
        if ($zlibEncoded === false) {
            throw new AssumptionFailedError("ZLIB compression failed");
        }

        $this->encodedData = $zlibEncoded;
        foreach (str_split(base64_encode($this->encodedData), 76) as $line) {
            $this->addLine($line);
        }
        $this->addLine("===END CRASH DUMP===");
    }

    private function pluginsData(): void {
        if ($this->server->getPluginManager() instanceof PluginManager) {
            $this->addLine();
            $this->addLine("Loaded plugins:");
            $this->data["plugins"] = [];
            $plugins = $this->server->getPluginManager()->getPlugins();
            ksort($plugins, SORT_STRING);

            foreach ($plugins as $plugin) {
                $this->addPluginData($plugin);
            }
        }
    }

    private function addPluginData($plugin): void {
        $description = $plugin->getDescription();
        $this->data["plugins"][$description->getName()] = [
            "name" => $description->getName(),
            "version" => $description->getVersion(),
            "authors" => $description->getAuthors(),
            "api" => $description->getCompatibleApis(),
            "enabled" => $plugin->isEnabled(),
            "depends" => $description->getDepend(),
            "softDepends" => $description->getSoftDepend(),
            "main" => $description->getMain(),
            "load" => $description->getOrder() === PluginLoadOrder::POSTWORLD ? "POSTWORLD" : "STARTUP",
            "website" => $description->getWebsite()
        ];
        $this->addLine("{$description->getName()} {$description->getVersion()} by " . implode(", ", $description->getAuthors()) . " for API(s) " . implode(", ", $description->getCompatibleApis()));
    }

    private function extraData(): void {
        global $argv;

        if ($this->server->getProperty("auto-report.send-settings", false)) {
            $this->data["parameters"] = (array) $argv;
            $this->data["server.properties"] = $this->readFileWithSensitiveData($this->server->getDataPath() . "server.properties");
            $this->data["pocketmine.yml"] = $this->readFile($this->server->getDataPath() . "pocketmine.yml");
        } else {
            $this->data["parameters"] = [];
            $this->data["server.properties"] = "";
            $this->data["pocketmine.yml"] = "";
        }

        $this->data["extensions"] = $this->getLoadedExtensions();

        if ($this->server->getProperty("auto-report.send-phpinfo", true)) {
            $this->data["phpinfo"] = $this->getPhpInfo();
        }
    }

    private function readFileWithSensitiveData(string $filePath): string {
        $fileContents = @file_get_contents($filePath);
        if ($fileContents !== false) {
            return preg_replace("#^rcon\\.password=(.*)$#m", "rcon.password=******", $fileContents);
        }
        return "";
    }

    private function readFile(string $filePath): string {
        return @file_get_contents($filePath) ?: "";
    }

    private function getLoadedExtensions(): array {
        $extensions = [];
        foreach (get_loaded_extensions() as $ext) {
            $extensions[$ext] = phpversion($ext);
        }
        return $extensions;
    }

    private function getPhpInfo(): string {
        ob_start();
        phpinfo();
        $phpInfo = ob_get_contents();
        ob_end_clean();
        return $phpInfo;
    }

    private function baseCrash(): void {
        global $lastExceptionError, $lastError;

        $error = $this->getError($lastExceptionError);
        $this->data["error"] = $error;
        $this->addLine("Error: {$error["message"]}");
        $this->addLine("File: {$error["file"]}");
        $this->addLine("Line: {$error["line"]}");
        $this->addLine("Type: {$error["type"]}");

        $this->determinePluginInvolvement($error);
        $this->addCode($error);
        $this->addBacktrace($error);
    }

    private function getError($lastExceptionError): array {
        global $lastError;

        if (isset($lastExceptionError)) {
            return $lastExceptionError;
        }

        $error = error_get_last();
        if ($error === null) {
            throw new \RuntimeException("Crash error information missing - did something use exit()?");
        }

        $error["trace"] = Utils::currentTrace(3);
        $errorConversion = [
            E_ERROR => "E_ERROR", E_WARNING => "E_WARNING", E_PARSE => "E_PARSE", E_NOTICE => "E_NOTICE",
            E_CORE_ERROR => "E_CORE_ERROR", E_CORE_WARNING => "E_CORE_WARNING", E_COMPILE_ERROR => "E_COMPILE_ERROR",
            E_COMPILE_WARNING => "E_COMPILE_WARNING", E_USER_ERROR => "E_USER_ERROR", E_USER_WARNING => "E_USER_WARNING",
            E_USER_NOTICE => "E_USER_NOTICE", E_STRICT => "E_STRICT", E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
            E_DEPRECATED => "E_DEPRECATED", E_USER_DEPRECATED => "E_USER_DEPRECATED"
        ];

        $error["fullFile"] = $error["file"];
        $error["file"] = Utils::cleanPath($error["file"]);
        $error["type"] = $errorConversion[$error["type"]] ?? $error["type"];
        $error["message"] = strtok($error["message"], "\n");

        return $error;
    }

    private function determinePluginInvolvement(array $error): void {
        if (!$this->determinePluginFromFile($error["fullFile"], true)) {
            foreach ($error["trace"] as $frame) {
                if (isset($frame["file"]) && $this->determinePluginFromFile($frame["file"], false)) {
                    break;
                }
            }
        }
    }

    private function addCode(array $error): void {
        if ($this->server->getProperty("auto-report.send-code", false) && file_exists($error["fullFile"])) {
            $file = @file($error["fullFile"], FILE_IGNORE_NEW_LINES);
            if ($file !== false) {
                for ($l = max(0, $error["line"] - 10); $l < $error["line"] + 10 && isset($file[$l]); ++$l) {
                    $this->addLine("[" . ($l + 1) . "] " . $file[$l]);
                    $this->data["code"][$l + 1] = $file[$l];
                }
            }
        }
    }

    private function addBacktrace(array $error): void {
        $this->addLine();
        $this->addLine("Backtrace:");
        foreach (($this->data["trace"] = Utils::printableTrace($error["trace"])) as $line) {
            $this->addLine($line);
        }
        $this->addLine();
    }

    private function determinePluginFromFile(string $filePath, bool $crashFrame): bool {
        $frameCleanPath = Utils::cleanPath($filePath);
        if (strpos($frameCleanPath, Utils::CLEAN_PATH_SRC_PREFIX) !== 0) {
            $this->addLine();
            $this->addLine($crashFrame ? "THIS CRASH WAS CAUSED BY A PLUGIN" : "A PLUGIN WAS INVOLVED IN THIS CRASH");
            $this->data["plugin_involvement"] = $crashFrame ? self::PLUGIN_INVOLVEMENT_DIRECT : self::PLUGIN_INVOLVEMENT_INDIRECT;

            if (file_exists($filePath)) {
                $this->findPluginForFile($filePath);
            }
            return true;
        }
        return false;
    }

    private function findPluginForFile(string $filePath): void {
        foreach ($this->server->getPluginManager()->getPlugins() as $plugin) {
            $pluginFile = Utils::cleanPath($plugin->getFile());
            if (strpos($filePath, $pluginFile) === 0) {
                $this->data["plugin"] = $plugin->getName();
                $this->addLine("BAD PLUGIN: " . $plugin->getDescription()->getFullName());
                break;
            }
        }
    }

    private function generalData(): void {
        $this->data["general"] = [
            "name" => $this->server->getName(),
            "protocol" => ProtocolInfo::CURRENT_PROTOCOL,
            "api" => EssentialInfo::API_VERSION,
            "git" => \pocketmine\GIT_COMMIT,
            "raklib" => RakLib::VERSION,
            "uname" => php_uname("a"),
            "php" => phpversion(),
            "zend" => zend_version(),
            "php_os" => PHP_OS,
            "os" => Utils::getOS(),
        ];

        $this->addLine("{$this->server->getName()} version: " . \pocketmine\GIT_COMMIT . " [Protocol " . ProtocolInfo::CURRENT_PROTOCOL . "; API " . EssentialInfo::API_VERSION . "]");
        $this->addLine("Git commit: " . \pocketmine\GIT_COMMIT);
        $this->addLine("uname -a: " . php_uname("a"));
        $this->addLine("PHP version: " . phpversion());
        $this->addLine("Zend version: " . zend_version());
        $this->addLine("OS: " . PHP_OS . ", " . Utils::getOS());
        $this->addLine();
        $this->addLine("Server uptime: " . $this->server->getUptime());
        $this->addLine("Number of loaded worlds: " . count($this->server->getLevels()));
        $this->addLine("Players online: " . count($this->server->getOnlinePlayers()) . "/" . $this->server->getMaxPlayers());
    }

    public function addLine(string $line = ""): void {
        fwrite($this->fp, $line . PHP_EOL);
    }

    public function add(string $str): void {
        fwrite($this->fp, $str);
    }
}
