<?php



namespace pocketmine\utils;

#include <rules/BinaryIO.h>

use pocketmine\item\Item;
use function chr;
use function ord;
use function strlen;
use function substr;

class BinaryStream{

	/** @var int */
	public $offset;
	/** @var string */
	public $buffer;

	public function __construct(string $buffer = "", int $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	public function reset(){
		$this->buffer = "";
		$this->offset = 0;
	}

	/**
	 * Rewinds the stream pointer to the beginning.
	 */
	public function rewind() : void{
		$this->offset = 0;
	}

	public function setOffset(int $offset) : void{
		$this->offset = $offset;
	}

	public function setBuffer(string $buffer = "", int $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	public function getOffset() : int{
		return $this->offset;
	}

	public function getBuffer() : string{
		return $this->buffer;
	}

	/**
	 * @param int|true $len
	 *
	 * @throws BinaryDataException if there are not enough bytes left in the buffer
	 */
	public function get($len) : string{
		if($len === 0){
			return "";
		}

		$buflen = strlen($this->buffer);
		if($len === true){
			$str = substr($this->buffer, $this->offset);
			$this->offset = $buflen;
			return $str;
		}
		if($len < 0){
			$this->offset = $buflen - 1;
			return "";
		}
		$remaining = $buflen - $this->offset;
		if($remaining < $len){
			throw new BinaryDataException("Not enough bytes left in buffer: need $len, have $remaining");
		}

		return $len === 1 ? $this->buffer[$this->offset++] : substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getRemaining() : string{
		$buflen = strlen($this->buffer);
		if($this->offset >= $buflen){
			throw new BinaryDataException("No bytes left to read");
		}
		$str = substr($this->buffer, $this->offset);
		$this->offset = $buflen;
		return $str;
	}

	public function put($str) : void{
		$this->buffer .= $str;
	}

	public function getBool() : bool{
		return $this->get(1) !== "\x00";
	}

	public function putBool(bool $v) : void{
		$this->buffer .= ($v ? "\x01" : "\x00");
	}

	public function getByte() : int{
		return ord($this->get(1));
	}

	public function putByte($v) : void{
		$this->buffer .= chr($v);
	}

	/**
	 * @return int
	 */
	public function getLong(){
		return Binary::readLong($this->get(8));
	}

	/**
	 * @param int $v
	 */
	public function putLong($v){
		$this->buffer .= Binary::writeLong($v);
	}

	public function getInt() : int{
		return Binary::readInt($this->get(4));
	}

	/**
	 * @param $v
	 */
	public function putInt($v){
		$this->buffer .= Binary::writeInt($v);
	}

	/**
	 * @return int
	 */
	public function getLLong(){
		return Binary::readLLong($this->get(8));
	}

	/**
	 * @param int $v
	 */
	public function putLLong($v){
		$this->buffer .= Binary::writeLLong($v);
	}

	/**
	 * @return int
	 */
	public function getLInt(){
		return Binary::readLInt($this->get(4));
	}

	/**
	 * @param $v
	 */
	public function putLInt($v){
		$this->buffer .= Binary::writeLInt($v);
	}

	/**
	 * @param $v
	 */
	public function putShort($v){
		$this->buffer .= Binary::writeShort($v);
	}

	public function getShort(){
		return Binary::readShort($this->get(2));
	}

	/**
	 * @return int
	 */
	public function getSignedShort(){
		return Binary::readSignedShort($this->get(2));
	}

	/**
	 * @param $v
	 */
	public function putSignedShort($v){
		$this->buffer .= Binary::writeShort($v);
	}

	public function getFloat(){
		return Binary::readFloat($this->get(4));
	}

	public function getRoundedFloat(int $accuracy){
		return Binary::readRoundedFloat($this->get(4), $accuracy);
	}

	/**
	 * @param $v
	 */
	public function putFloat($v){
		$this->buffer .= Binary::writeFloat($v);
	}

	/**
	 * @param bool $signed
	 *
	 * @return int
	 */
	public function getLShort($signed = true){
		return $signed ? Binary::readSignedLShort($this->get(2)) : Binary::readLShort($this->get(2));
	}

	public function getSignedLShort(){
		return Binary::readSignedLShort($this->get(2));
	}

	/**
	 * @param $v
	 */
	public function putLShort($v){
		$this->buffer .= Binary::writeLShort($v);
	}

	public function getLFloat(){
		return Binary::readLFloat($this->get(4));
	}

	public function getRoundedLFloat(int $accuracy){
		return Binary::readRoundedLFloat($this->get(4), $accuracy);
	}

	/**
	 * @param $v
	 */
	public function putLFloat($v){
		$this->buffer .= Binary::writeLFloat($v);
	}

	/**
	 * @return mixed
	 */
	public function getTriad(){
		return Binary::readTriad($this->get(3));
	}

	/**
	 * @param $v
	 */
	public function putTriad($v){
		$this->buffer .= Binary::writeTriad($v);
	}

	/**
	 * @return mixed
	 */
	public function getLTriad(){
		return Binary::readLTriad($this->get(3));
	}

	/**
	 * @param $v
	 */
	public function putLTriad($v){
		$this->buffer .= Binary::writeLTriad($v);
	}

	public function getString(){
		return $this->get($this->getUnsignedVarInt());
	}

	public function putString($v){
		$this->putUnsignedVarInt(strlen($v));
		$this->put($v);
	}

	public function getUUID() : UUID{
        //Actually these are two little-endian strings: UUID Most followed by UUID Least.
		$part1 = $this->getLInt();
		$part0 = $this->getLInt();
		$part3 = $this->getLInt();
		$part2 = $this->getLInt();
		return new UUID($part0, $part1, $part2, $part3);
	}

	public function putUUID(UUID $uuid){
		$this->putLInt($uuid->getPart(1));
		$this->putLInt($uuid->getPart(0));
		$this->putLInt($uuid->getPart(3));
		$this->putLInt($uuid->getPart(2));
	}

	public function getSlot() : Item{
		$id = $this->getVarInt();

		if($id <= 0){
			return Item::get(0, 0, 0);
		}
		$auxValue = $this->getVarInt();
		$data = $auxValue >> 8;
		if($data === 0x7fff){
			$data = -1;
		}
		$cnt = $auxValue & 0xff;

		$nbtLen = $this->getLShort();
		$nbt = "";

		if($nbtLen > 0){
			$nbt = $this->get($nbtLen);
		}

        $canPlaceOn = $this->getVarInt();
        if($canPlaceOn > 0){
            for($i = 0; $i < $canPlaceOn && !$this->feof(); ++$i){
                $this->getString();
            }
        }

		$canDestroy = $this->getVarInt();
		if($canDestroy > 0){
			for($i = 0; $i < $canDestroy && !$this->feof(); ++$i){
				$this->getString();
			}
		}

		return Item::get($id, $data, $cnt, $nbt);
	}


	/**
	 * @param Item $item
	 */
	public function putSlot(Item $item){
		if($item->getId() === 0){
			$this->putVarInt(0);

			return;
		}

		$this->putVarInt($item->getId());
		$auxValue = (($item->getDamage() & 0x7fff) << 8) | $item->getCount();
		$this->putVarInt($auxValue);
		$nbt = $item->getCompoundTag();
		$this->putLShort(strlen($nbt));
		$this->put($nbt);

		$this->putVarInt(0); //CanPlaceOn entry count (TODO)
		$this->putVarInt(0); //CanDestroy entry count (TODO)
	}

	/**
	 * Reads an unsigned varint32 from the stream.
	 */
	public function getUnsignedVarInt() : int{
		return Binary::readUnsignedVarInt($this->buffer, $this->offset);
	}

    /**
     * Writes an unsigned varint32 to the stream.
     *
     * @param int $v
     */
	public function putUnsignedVarInt(int $v){
		$this->put(Binary::writeUnsignedVarInt($v));
	}

	/**
	 * Reads a signed varint32 from a stream.
	 */
	public function getVarInt() : int{
		return Binary::readVarInt($this->buffer, $this->offset);
	}

	/**
	 * Reads a variable-length 64-bit integer from the buffer and returns it.
	 * @return int
	 */
	public function getUnsignedVarLong(){
		return Binary::readUnsignedVarLong($this->buffer, $this->offset);
	}

	/**
	 * Writes a 64-bit variable-length integer to the end of the buffer.
	 * @param int $v
	 */
	public function putUnsignedVarLong($v){
		$this->buffer .= Binary::writeUnsignedVarLong($v);
	}

	/**
	 * Reads a 64-bit zigzag-encoded variable-length integer from the buffer and returns it.
	 * @return int
	 */
	public function getVarLong(){
		return Binary::readVarLong($this->buffer, $this->offset);
	}

	/**
	 * Writes a variable-length 64-bit zigzag integer to the end of the buffer.
	 * @param int $v
	 */
	public function putVarLong($v){
		$this->buffer .= Binary::writeVarLong($v);
	}

	/**
	 * Writes a zigzag-encoded 32-bit variable-length integer to the end of the buffer.
	 */
	public function putVarInt($v) : void{
		$this->put(Binary::writeVarInt($v));
	}

	/**
	 * @return int
	 */
	public function getEntityId(){
		return $this->getVarInt();
	}

	/**
	 * @param $v
	 */
	public function putEntityId($v){
		$this->putVarInt($v);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function getBlockCoords(&$x, &$y, &$z){
		$x = $this->getVarInt();
		$y = $this->getUnsignedVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Reads the signed position of the block with the Y coordinate.
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getSignedBlockCoords(&$x, &$y, &$z){
		$x = $this->getVarInt();
		$y = $this->getVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function putBlockCoords($x, $y, $z){
		$this->putVarInt($x);
		$this->putUnsignedVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Writes the signed position of the block with the Y coordinate.
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function putSignedBlockCoords(int $x, int $y, int $z){
		$this->putVarInt($x);
		$this->putVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function getVector3f(&$x, &$y, &$z){
		$x = $this->getRoundedLFloat(4);
		$y = $this->getRoundedLFloat(4);
		$z = $this->getRoundedLFloat(4);
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function putVector3f($x, $y, $z){
		$this->putLFloat($x);
		$this->putLFloat($y);
		$this->putLFloat($z);
	}

	/**
	 * Returns whether the offset has reached the end of the buffer.
	 * @return bool
	 */
	public function feof() : bool{
		return !isset($this->buffer[$this->offset]);
	}
}