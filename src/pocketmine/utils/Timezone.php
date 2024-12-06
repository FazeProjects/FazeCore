<?php



declare(strict_types=1);

namespace pocketmine\utils;

abstract class Timezone{

	public static function get() : string{
		return ini_get('date.timezone');
	}

	public static function init() : array{
		$messages = [];
		do{
			$timezone = ini_get("date.timezone");
			if($timezone !== ""){
                /*
                 * This is done to prevent people from coming to us with complaints and filling the bug tracker when they set
                 * what seems to be an incorrect timezone abbreviation in php.ini.
                 */
                if(strpos($timezone, "/") === false){
					$default_timezone = timezone_name_from_abbr($timezone);
					if($default_timezone !== false){
						ini_set("date.timezone", $default_timezone);
						date_default_timezone_set($default_timezone);
						break;
					}else{
                        // Invalid value in php.ini, try another method of timezone detection
                        $messages[] = "Timezone \"$timezone\" could not be parsed as a valid timezone from php.ini, falling back to auto-detection";
                    }
				}else{
					date_default_timezone_set($timezone);
					break;
				}
			}

            if(($timezone = self::detectSystemTimezone()) and date_default_timezone_set($timezone)){
                // Success! The timezone is already set and checked in the if statement.
                // This is here just for redundancy in case some program wants to read timezone data from ini.
                ini_set("date.timezone", $timezone);
                break;
            }

            if(($response = Internet::getURL("http://ip-api.com/json")) !== false // If the system timezone cannot be determined or the timezone is an invalid value.
                and $ip_geolocation_data = json_decode($response, true)
                and $ip_geolocation_data['status'] !== 'fail'
                and date_default_timezone_set($ip_geolocation_data['timezone'])
            ){
                // Again, for redundancy.
                ini_set("date.timezone", $ip_geolocation_data['timezone']);
                break;
            }

            ini_set("date.timezone", "UTC");
            date_default_timezone_set("UTC");
            $messages[] = "The timezone cannot be automatically determined or it has been set to an invalid value. An incorrect timezone will lead to incorrect timestamps in console logs. It has been set to \"UTC\" by default. You can change it in the php.ini file.";
        }while(false);

		return $messages;
	}

    public static function detectSystemTimezone() {
        switch(Utils::getOS()) {
            case 'win':
                $regex = '/(UTC)([+\-]\d{2}:\d{2})/';

                exec("wmic timezone get Caption", $output);

                $string = trim(implode("\n", $output));

                preg_match($regex, $string, $matches);

                if (!isset($matches[2])) {
                    return false;
                }

                $offset = $matches[2];

                // Caso não haja offset
                if ($offset == "") {
                    return "UTC";
                }

                return self::parseOffset($offset);

            case 'linux':
                // Ubuntu / Debian
                if (file_exists('/etc/timezone')) {
                    $data = file_get_contents('/etc/timezone');
                    if ($data) {
                        return trim($data);
                    }
                }

                // RHEL / CentOS
                if (file_exists('/etc/sysconfig/clock')) {
                    $data = parse_ini_file('/etc/sysconfig/clock');
                    if (!empty($data['ZONE'])) {
                        return trim($data['ZONE']);
                    }
                }

                // Portable method for incompatible Linux distributions
                $offset = trim(exec('date +%:z'));

                if ($offset == "+00:00") {
                    return "UTC";
                }

                return self::parseOffset($offset);

            case 'mac':
                if (is_link('/etc/localtime')) {
                    $filename = readlink('/etc/localtime');
                    if (strpos($filename, '/usr/share/zoneinfo/') === 0) {
                        $timezone = substr($filename, 20);
                        return trim($timezone);
                    }
                }
                return false;

            default:
                return false;
        }
    }



    /**
	 * @param string $offset In the format of +09:00, +02:00, -04:00 etc.
	 *
	 * @return string|bool
	 */
	private static function parseOffset($offset){
		//Make signed offsets unsigned for date_parse
		if(strpos($offset, '-') !== false){
			$negative_offset = true;
			$offset = str_replace('-', '', $offset);
		}else{
			if(strpos($offset, '+') !== false){
				$negative_offset = false;
				$offset = str_replace('+', '', $offset);
			}else{
				return false;
			}
		}

		$parsed = date_parse($offset);
		$offset = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

// After date_parse finishes, we return the sign back
		if($negative_offset == true){
			$offset = -abs($offset);
		}

// Then look at the offset upwards.
// timezone_name_from_abbr is not used because it returns false for some (most) offsets due to its strange matching function.
// This is a bug in PHP since 2008!
		foreach(timezone_abbreviations_list() as $zones){
			foreach($zones as $timezone){
				if($timezone['offset'] == $offset){
					return $timezone['timezone_id'];
				}
			}
		}

		return false;
	}
}