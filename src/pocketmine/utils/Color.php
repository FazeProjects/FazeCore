<?php



namespace pocketmine\utils;

use SplFixedArray;

class Color {

	const COLOR_DYE_BLACK = 0;//dye colors
	const COLOR_DYE_RED = 1;
	const COLOR_DYE_GREEN = 2;
	const COLOR_DYE_BROWN = 3;
	const COLOR_DYE_BLUE = 4;
	const COLOR_DYE_PURPLE = 5;
	const COLOR_DYE_CYAN = 6;
	const COLOR_DYE_LIGHT_GRAY = 7;
	const COLOR_DYE_GRAY = 8;
	const COLOR_DYE_PINK = 9;
	const COLOR_DYE_LIME = 10;
	const COLOR_DYE_YELLOW = 11;
	const COLOR_DYE_LIGHT_BLUE = 12;
	const COLOR_DYE_MAGENTA = 13;
	const COLOR_DYE_ORANGE = 14;
	const COLOR_DYE_WHITE = 15;

	private $alpha;
	private $red;
	private $green;
	private $blue;

	/** @var SplFixedArray */
	public static $dyeColors = null;

	public static function init(){
		if(self::$dyeColors === null){
			self::$dyeColors = new SplFixedArray(16); //What's the point of making a 256-long array for 16 objects?
			self::$dyeColors[self::COLOR_DYE_BLACK] = Color::getRGB(30, 27, 27);
			self::$dyeColors[self::COLOR_DYE_RED] = Color::getRGB(179, 49, 44);
			self::$dyeColors[self::COLOR_DYE_GREEN] = Color::getRGB(61, 81, 26);
			self::$dyeColors[self::COLOR_DYE_BROWN] = Color::getRGB(81, 48, 26);
			self::$dyeColors[self::COLOR_DYE_BLUE] = Color::getRGB(37, 49, 146);
			self::$dyeColors[self::COLOR_DYE_PURPLE] = Color::getRGB(123, 47, 190);
			self::$dyeColors[self::COLOR_DYE_CYAN] = Color::getRGB(40, 118, 151);
			self::$dyeColors[self::COLOR_DYE_LIGHT_GRAY] = Color::getRGB(153, 153, 153);
			self::$dyeColors[self::COLOR_DYE_GRAY] = Color::getRGB(67, 67, 67);
			self::$dyeColors[self::COLOR_DYE_PINK] = Color::getRGB(216, 129, 152);
			self::$dyeColors[self::COLOR_DYE_LIME] = Color::getRGB(65, 205, 52);
			self::$dyeColors[self::COLOR_DYE_YELLOW] = Color::getRGB(222, 207, 42);
			self::$dyeColors[self::COLOR_DYE_LIGHT_BLUE] = Color::getRGB(102, 137, 211);
			self::$dyeColors[self::COLOR_DYE_MAGENTA] = Color::getRGB(195, 84, 205);
			self::$dyeColors[self::COLOR_DYE_ORANGE] = Color::getRGB(235, 136, 68);
			self::$dyeColors[self::COLOR_DYE_WHITE] = Color::getRGB(240, 240, 240);
		}
	}

    /**
     * Returns a color from the provided ARGB color code (32-bit)
     * @param int $code
     * @return Color
     */
    public static function fromARGB(int $code) : Color{
        return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff, ($code >> 24) & 0xff);
    }

    /**
     * Returns a 32-bit ARGB color value.
     */
    public function toARGB() : int{
        return ($this->alpha << 24) | ($this->red << 16) | ($this->green << 8) | $this->blue;
    }

    /**
     * Returns a color from the provided RGBA color code (32-bit)
     * @param int $c
     * @return Color
     */
    public static function fromRGBA(int $c) : Color{
        return new Color(($c >> 24) & 0xff, ($c >> 16) & 0xff, ($c >> 8) & 0xff, $c & 0xff);
    }

    /**
     * Returns a 32-bit RGBA color value.
     */
    public function toRGBA() : int{
        return ($this->red << 24) | ($this->green << 16) | ($this->blue << 8) | $this->alpha;
    }

    /**
     * Returns a little-endian RGBA color value.
     */
    public function toABGR() : int{
        return ($this->alpha << 24) | ($this->blue << 16) | ($this->green << 8) | $this->red;
    }

    /**
     * @param int $code
     * @return Color
     */
    public static function fromABGR(int $code){
        return new Color($code & 0xff, ($code >> 8) & 0xff, ($code >> 16) & 0xff, ($code >> 24) & 0xff);
    }

	/**
	 * @param $r
	 * @param $g
	 * @param $b
	 *
	 * @return Color
	 */
	public static function getRGB($r, $g, $b){
		return new Color((int) $r, (int) $g, (int) $b);
	}

    /**
     * @param Color ...$colors
     *
     * @return Color
     */
	public static function averageColor(Color ...$colors){
		$tr = 0;//total red
		$tg = 0;//green
		$tb = 0;//blue
		$count = 0;
		foreach($colors as $c){
			$tr += $c->getRed();
			$tg += $c->getGreen();
			$tb += $c->getBlue();
			++$count;
		}
		return Color::getRGB($tr / $count, $tg / $count, $tb / $count);
	}

	/**
	 * @param $id
	 *
	 * @return mixed|Color
	 */
	public static function getDyeColor($id){
		if(isset(self::$dyeColors[$id])){
			return clone self::$dyeColors[$id];
		}
		return Color::getRGB(0, 0, 0);
	}

    /**
     * Returns a little-endian RGBA color value.
     *
     * @param $r
     * @param $g
     * @param $b
     * @param int $a
     */
	public function __construct($r, $g, $b, $a = 0xFF){
		$this->red = $r;
		$this->green = $g;
		$this->blue = $b;
		$this->alpha = $a;
	}

	/**
	 * @return int
	 */
	public function getRed(){
		return (int) $this->red;
	}

	/**
	 * @return int
	 */
	public function getBlue(){
		return (int) $this->blue;
	}

	/**
	 * @return int
	 */
	public function getGreen(){
		return (int) $this->green;
	}

	/**
	 * @return int
	 */
	public function getColorCode(){
		return ($this->red << 16 | $this->green << 8 | $this->blue) & 0xffffff;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return "Color(red:" . $this->red . ", green:" . $this->green . ", blue:" . $this->blue . ")";
	}
}