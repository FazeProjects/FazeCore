<?php

namespace pocketmine\entity;

final class HorseVariant
{

	public const VARIANT_WHITE = 0;
	public const VARIANT_WHITE_WHITE = 256;
	public const VARIANT_WHITE_WHITE_FIELD = 512;
	public const VARIANT_WHITE_WHITE_DOTS = 768;
	public const VARIANT_WHITE_BLACK_DOTS = 1024;

	public const VARIANT_CREAMY = 1;
	public const VARIANT_CREAMY_WHITE = 257;
	public const VARIANT_CREAMY_WHITE_FIELD = 513;
	public const VARIANT_CREAMY_WHITE_DOTS = 769;
	public const VARIANT_CREAMY_BLACK_DOTS = 1025;

	public const VARIANT_CHESTNUT = 2;
	public const VARIANT_CHESTNUT_WHITE = 258;
	public const VARIANT_CHESTNUT_WHITE_FIELD = 514;
	public const VARIANT_CHESTNUT_WHITE_DOTS = 770;
	public const VARIANT_CHESTNUT_BLACK_DOTS = 1026;

	public const VARIANT_BROWN = 3;
	public const VARIANT_BROWN_WHITE = 259;
	public const VARIANT_BROWN_WHITE_FIELD = 515;
	public const VARIANT_BROWN_WHITE_DOTS = 771;
	public const VARIANT_BROWN_BLACK_DOTS = 1027;

	public const VARIANT_BLACK = 4;
	public const VARIANT_BLACK_WHITE = 260;
	public const VARIANT_BLACK_WHITE_FIELD = 516;
	public const VARIANT_BLACK_WHITE_DOTS = 772;
	public const VARIANT_BLACK_BLACK_DOTS = 1028;

	public const VARIANT_GRAY = 5;
	public const VARIANT_GRAY_WHITE = 261;
	public const VARIANT_GRAY_WHITE_FIELD = 517;
	public const VARIANT_GRAY_WHITE_DOTS = 773;
	public const VARIANT_GRAY_BLACK_DOTS = 1029;

	public const VARIANT_DARK_BROWN = 6;
	public const VARIANT_DARK_BROWN_WHITE = 262;
	public const VARIANT_DARK_BROWN_WHITE_FIELD = 518;
	public const VARIANT_DARK_BROWN_WHITE_DOTS = 774;
	public const DARK_BROWN_BLACK_DOTS = 1030;

	public const ALLOWED_VARIANTS = [
		self::VARIANT_WHITE,
		self::VARIANT_WHITE_WHITE,
		self::VARIANT_WHITE_WHITE_FIELD,
		self::VARIANT_WHITE_WHITE_DOTS,
		self::VARIANT_WHITE_BLACK_DOTS,

		self::VARIANT_CREAMY,
		self::VARIANT_CREAMY_WHITE,
		self::VARIANT_CREAMY_WHITE_FIELD,
		self::VARIANT_CREAMY_WHITE_DOTS,
		self::VARIANT_CREAMY_BLACK_DOTS,

		self::VARIANT_CHESTNUT,
		self::VARIANT_CHESTNUT_WHITE,
		self::VARIANT_CHESTNUT_WHITE_FIELD,
		self::VARIANT_CHESTNUT_WHITE_DOTS,
		self::VARIANT_CHESTNUT_BLACK_DOTS,

		self::VARIANT_BROWN,
		self::VARIANT_BROWN_WHITE,
		self::VARIANT_BROWN_WHITE_FIELD,
		self::VARIANT_BROWN_WHITE_DOTS,
		self::VARIANT_BROWN_BLACK_DOTS,

		self::VARIANT_BLACK,
		self::VARIANT_BLACK_WHITE,
		self::VARIANT_BLACK_WHITE_FIELD,
		self::VARIANT_BLACK_WHITE_DOTS,
		self::VARIANT_BLACK_BLACK_DOTS,

		self::VARIANT_GRAY,
		self::VARIANT_GRAY_WHITE,
		self::VARIANT_GRAY_WHITE_FIELD,
		self::VARIANT_GRAY_WHITE_DOTS,
		self::VARIANT_GRAY_BLACK_DOTS,

		self::VARIANT_DARK_BROWN,
		self::VARIANT_DARK_BROWN_WHITE,
		self::VARIANT_DARK_BROWN_WHITE_FIELD,
		self::VARIANT_DARK_BROWN_WHITE_DOTS,
		self::DARK_BROWN_BLACK_DOTS,
	];

	private function __construct()
	{
	}

	public static function getRandomColor(): int
	{
		return self::ALLOWED_VARIANTS[array_rand(self::ALLOWED_VARIANTS)];
	}
}