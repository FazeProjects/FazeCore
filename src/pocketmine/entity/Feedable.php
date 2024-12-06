<?php

namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;

/**
 * Моб который можно кормить
 */
interface Feedable
{

	/**
	 * Определяет можно ли покормить моба
	 *
	 * @param ItemItem $item
	 *
	 * @return bool
	 */
	public function canFeed(ItemItem $item): bool;

	/**
	 * @param ItemItem $item
	 *
	 * @return void
	 */
	public function feed(ItemItem $item): void;
}