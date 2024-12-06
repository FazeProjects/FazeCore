<?php



namespace pocketmine\lang;

use pocketmine\event\TextContainer;
use pocketmine\event\TranslationContainer;

class BaseLang {

	const FALLBACK_LANGUAGE = "eng";

	protected $langName;

	protected $lang = [];
	protected $fallbackLang = [];

	/**
	 * BaseLang constructor.
	 *
	 * @param        $lang
	 * @param null   $path
	 * @param string $fallback
	 */
	public function __construct($lang, $path = null, $fallback = self::FALLBACK_LANGUAGE){

		$this->langName = strtolower($lang);

		if($path === null){
			$path = \pocketmine\PATH . "src/pocketmine/lang/locale/";
		}

		$this->loadLang($path . $this->langName . ".ini", $this->lang);
		$this->loadLang($path . $fallback . ".ini", $this->fallbackLang);
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->get("language.name");
	}

	/**
	 * @return string
	 */
	public function getLang(){
		return $this->langName;
	}

	/**
	 * @param       $path
	 * @param array $d
	 */
	protected function loadLang($path, array &$d){
		if(file_exists($path) and strlen($content = file_get_contents($path)) > 0){
			foreach(explode("\n", $content) as $line){
				$line = trim($line);
				if($line === "" or $line[0] === "#"){
					continue;
				}

				$t = explode("=", $line);
				if(count($t) < 2){
					continue;
				}

				$key = trim(array_shift($t));
				$value = trim(implode("=", $t));

				if($value === ""){
					continue;
				}

				$d[$key] = $value;
			}
		}
	}

	/**
	 * @param string      $str
	 * @param string[]    $params
	 * @param string|null $onlyPrefix
	 *
	 * @return string
	 */
	public function translateString($str, array $params = [], $onlyPrefix = null){
		$baseText = $this->get($str);
		$baseText = $this->parseTranslation(($baseText !== null and ($onlyPrefix === null or strpos($str, $onlyPrefix) === 0)) ? $baseText : $str, $onlyPrefix);

		foreach($params as $i => $p){
			$baseText = str_replace("{%$i}", $this->parseTranslation($p), $baseText, $onlyPrefix);
		}

		return str_replace("%0", "", $baseText); //исправляет ошибку клиента, из-за которой %0 в переводе вызывал зависание
	}

	/**
	 * @param TextContainer $c
	 *
	 * @return mixed|null|string
	 */
	public function translate(TextContainer $c){
		if($c instanceof TranslationContainer){
			$baseText = $this->internalGet($c->getText());
			$baseText = $this->parseTranslation($baseText !== null ? $baseText : $c->getText());

			foreach($c->getParameters() as $i => $p){
				$baseText = str_replace("{%$i}", $this->parseTranslation($p), $baseText);
			}
		}else{
			$baseText = $this->parseTranslation($c->getText());
		}

		return $baseText;
	}

	/**
	 * @param $id
	 *
	 * @return mixed|null
	 */
	public function internalGet($id){
		if(isset($this->lang[$id])){
			return $this->lang[$id];
		}elseif(isset($this->fallbackLang[$id])){
			return $this->fallbackLang[$id];
		}

		return null;
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function get($id){
		if(isset($this->lang[$id])){
			return $this->lang[$id];
		}elseif(isset($this->fallbackLang[$id])){
			return $this->fallbackLang[$id];
		}

		return $id;
	}

    /**
     * @param string $text
     * @param string|null $onlyPrefix
     *
     * @return string
     */
    protected function parseTranslation(string $text, ?string $onlyPrefix = null) : string{
        $newString = "";

        $replaceString = null;

        $len = strlen($text);
        for($i = 0; $i < $len; ++$i){
            $c = $text[$i];
            if($replaceString !== null){
                $ord = ord($c);
                if(
                    ($ord >= 0x30 && $ord <= 0x39) // 0-9
                    || ($ord >= 0x41 && $ord <= 0x5a) // A-Z
                    || ($ord >= 0x61 && $ord <= 0x7a) || // a-z
                    $c === "." || $c === "-"
                ){
                    $replaceString .= $c;
                }else{
                    if(($t = $this->internalGet(substr($replaceString, 1))) !== null && ($onlyPrefix === null || strpos($replaceString, $onlyPrefix) === 1)){
                        $newString .= $t;
                    }else{
                        $newString .= $replaceString;
                    }
                    $replaceString = null;

                    if($c === "%"){
                        $replaceString = $c;
                    }else{
                        $newString .= $c;
                    }
                }
            }elseif($c === "%"){
                $replaceString = $c;
            }else{
                $newString .= $c;
            }
        }

        if($replaceString !== null){
            if(($t = $this->internalGet(substr($replaceString, 1))) !== null && ($onlyPrefix === null || strpos($replaceString, $onlyPrefix) === 1)){
                $newString .= $t;
            }else{
                $newString .= $replaceString;
            }
        }

        return $newString;
    }
}
