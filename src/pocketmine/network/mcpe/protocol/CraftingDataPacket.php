<?php



namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\inventory\FurnaceRecipe;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\Item;
use pocketmine\utils\BinaryStream;

class CraftingDataPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CRAFTING_DATA_PACKET;

	const ENTRY_SHAPELESS = 0;
	const ENTRY_SHAPED = 1;
	const ENTRY_FURNACE = 2;
	const ENTRY_FURNACE_DATA = 3;
	const ENTRY_MULTI = 4; //TODO
	const ENTRY_SHULKER_BOX = 5; //TODO

	/** @var object[] */
	public $entries = [];
	public $cleanRecipes = false;

	/**
	 * @return $this
	 */
	public function clean(){
		$this->entries = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		$entries = [];
		$recipeCount = $this->getUnsignedVarInt();
		for($i = 0; $i < $recipeCount && !$this->feof(); ++$i){
			$entry = [];
			$entry["type"] = $recipeType = $this->getVarInt();

			switch($recipeType){
				case self::ENTRY_SHAPELESS:
				case self::ENTRY_SHULKER_BOX:
					$ingredientCount = $this->getUnsignedVarInt();
					/** @var Item */
					$entry["input"] = [];
					for($j = 0; $j < $ingredientCount && !$this->feof(); ++$j){
						$entry["input"][] = $this->getSlot();
					}
					$resultCount = $this->getUnsignedVarInt();
					$entry["output"] = [];
					for($k = 0; $k < $resultCount && !$this->feof(); ++$k){
						$entry["output"][] = $this->getSlot();
					}
					$entry["uuid"] = $this->getUUID()->toString();

					break;
				case self::ENTRY_SHAPED:
					$entry["width"] = $this->getVarInt();
					$entry["height"] = $this->getVarInt();
					$count = $entry["width"] * $entry["height"];
					$entry["input"] = [];
					for($j = 0; $j < $count && !$this->feof(); ++$j){
						$entry["input"][] = $this->getSlot();
					}
					$resultCount = $this->getUnsignedVarInt();
					$entry["output"] = [];
					for($k = 0; $k < $resultCount && !$this->feof(); ++$k){
						$entry["output"][] = $this->getSlot();
					}
					$entry["uuid"] = $this->getUUID()->toString();
					break;
				case self::ENTRY_FURNACE:
				case self::ENTRY_FURNACE_DATA:
					$entry["inputId"] = $this->getVarInt();
					if($recipeType === self::ENTRY_FURNACE_DATA){
						$entry["inputDamage"] = $this->getVarInt();
					}
					$entry["output"] = $this->getSlot();
					break;
				case self::ENTRY_MULTI:
					$entry["uuid"] = $this->getUUID()->toString();
					break;
				default:
					throw new \UnexpectedValueException("Unhandled recipe type $recipeType!"); //do not continue attempting to decode
			}
			$entries[] = $entry;
		}
		$this->getBool(); //cleanRecipes
	}

	/**
	 * @param              $entry
	 * @param BinaryStream $stream
	 *
	 * @return int
	 */
	private static function writeEntry($entry, BinaryStream $stream){
		if($entry instanceof ShapelessRecipe){
			return self::writeShapelessRecipe($entry, $stream);
		}elseif($entry instanceof ShapedRecipe){
			return self::writeShapedRecipe($entry, $stream);
		}elseif($entry instanceof FurnaceRecipe){
			return self::writeFurnaceRecipe($entry, $stream);
		}

		//TODO: add MultiRecipe

		return -1;
	}

	/**
	 * @param ShapelessRecipe $recipe
	 * @param BinaryStream    $stream
	 *
	 * @return int
	 */
	private static function writeShapelessRecipe(ShapelessRecipe $recipe, BinaryStream $stream){
		$stream->putUnsignedVarInt($recipe->getIngredientCount());
		foreach($recipe->getIngredientList() as $item){
			$stream->putSlot($item);
		}

		$stream->putUnsignedVarInt(1);
		$stream->putSlot($recipe->getResult());

		$stream->putUUID($recipe->getId());

		return CraftingDataPacket::ENTRY_SHAPELESS;
	}

	/**
	 * @param ShapedRecipe $recipe
	 * @param BinaryStream $stream
	 *
	 * @return int
	 */
	private static function writeShapedRecipe(ShapedRecipe $recipe, BinaryStream $stream){
		$stream->putVarInt($recipe->getWidth());
		$stream->putVarInt($recipe->getHeight());

		for($z = 0; $z < $recipe->getHeight(); ++$z){
			for($x = 0; $x < $recipe->getWidth(); ++$x){
				$stream->putSlot($recipe->getIngredient($x, $z));
			}
		}

		$stream->putUnsignedVarInt(1);
		$stream->putSlot($recipe->getResult());

		$stream->putUUID($recipe->getId());

		return CraftingDataPacket::ENTRY_SHAPED;
	}

	/**
	 * @param FurnaceRecipe $recipe
	 * @param BinaryStream  $stream
	 *
	 * @return int
	 */
	private static function writeFurnaceRecipe(FurnaceRecipe $recipe, BinaryStream $stream){
		if(!$recipe->getInput()->hasAnyDamageValue()){ //Data recipe
			$stream->putVarInt($recipe->getInput()->getId());
			$stream->putVarInt($recipe->getInput()->getDamage());
			$stream->putSlot($recipe->getResult());

			return CraftingDataPacket::ENTRY_FURNACE_DATA;
		}else{
			$stream->putVarInt($recipe->getInput()->getId());
			$stream->putSlot($recipe->getResult());

			return CraftingDataPacket::ENTRY_FURNACE;
		}
	}

	/**
	 * @param ShapelessRecipe $recipe
	 */
	public function addShapelessRecipe(ShapelessRecipe $recipe){
		$this->entries[] = $recipe;
	}

	/**
	 * @param ShapedRecipe $recipe
	 */
	public function addShapedRecipe(ShapedRecipe $recipe){
		$this->entries[] = $recipe;
	}

	/**
	 * @param FurnaceRecipe $recipe
	 */
	public function addFurnaceRecipe(FurnaceRecipe $recipe){
		$this->entries[] = $recipe;
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putUnsignedVarInt(count($this->entries));

		$writer = new BinaryStream();
		foreach($this->entries as $d){
			$entryType = self::writeEntry($d, $writer);
			if($entryType >= 0){
				$this->putVarInt($entryType);
				$this->put($writer->getBuffer());
			}else{
				$this->putVarInt(-1);
			}

			$writer->reset();
		}

		$this->putBool($this->cleanRecipes);
	}

	/**
	 * @return string Current packet name
	 */
	public function getName(){
		return "CraftingDataPacket";
	}

}
