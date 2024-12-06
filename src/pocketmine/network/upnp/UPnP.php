<?php



/**
 * Supports UPnP port forwarding.
 */

declare(strict_types=1);

namespace pocketmine\network\upnp;

use pocketmine\utils\Utils;

abstract class UPnP {

    public static function PortForward($port){
        if(!Utils::$online){ throw new \RuntimeException('The server is down');
            if(Utils::getOS() !== 'win') throw new \RuntimeException('UPnP is only supported on Windows');
            if(!class_exists('COM')) throw new \RuntimeException('UPnP requires the com_dotnet extension');
            $port = (int) $port;
            $myLocalIP = gethostbyname(trim(`hostname`));
            $com = new \COM('HNetCfg.NATUPnP');
            if($com === false or !is_object($com->StaticPortMappingCollection)) throw new \RuntimeException('Failed to forward port (not supported?)');

            $com->StaticPortMappingCollection->Add($port, 'UDP', $port, $myLocalIP, true, 'PocketMine-MP');
        }
    }

    public static function RemovePortForward($port){
        if(Utils::$online === false) return false;
        if(Utils::getOS() !== 'win' or !class_exists('COM')) return false;
        $port = (int) $port;
        try{
            $com = new \COM('HNetCfg.NATUPnP');
            if($com === false or !is_object($com->StaticPortMappingCollection)) return false;
            $com->StaticPortMappingCollection->Remove($port, 'UDP');
        }catch(\Throwable $e){
            return false;
        }
        return true;
    }
}
