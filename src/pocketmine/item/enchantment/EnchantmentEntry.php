<?php



namespace pocketmine\item\enchantment;


class EnchantmentEntry {

	/** @var Enchantment[] */
	private $enchantments;
	private $cost;
	private $randomName;

	/**
	 * @param Enchantment[] $enchantments
	 * @param               $cost
	 * @param               $randomName
	 */
	public function __construct(array $enchantments, $cost, $randomName){
		$this->enchantments = $enchantments;
		$this->cost = (int) $cost;
		$this->randomName = $randomName;
	}

	/**
	 * @return array|Enchantment[]
	 */
	public function getEnchantments(){
		return $this->enchantments;
	}

	/**
	 * @return int
	 */
	public function getCost(){
		return $this->cost;
	}

	public function getRandomName(){
		return $this->randomName;
	}

}