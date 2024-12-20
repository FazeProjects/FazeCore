<?php



namespace pocketmine\math;

use function assert;
use function implode;
use function max;
use function substr;

class Matrix implements \ArrayAccess{
	private $matrix = [];
	private $rows = 0;
	private $columns = 0;

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset){
		return isset($this->matrix[(int) $offset]);
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset){
		return $this->matrix[(int) $offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value){
		$this->matrix[(int) $offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset){
		unset($this->matrix[(int) $offset]);
	}

	/**
	 * Matrix constructor.
	 *
	 * @param       $rows
	 * @param       $columns
	 * @param array $set
	 */
	public function __construct($rows, $columns, array $set = []){
		$this->rows = max(1, (int) $rows);
		$this->columns = max(1, (int) $columns);
		$this->set($set);
	}

	/**
	 * @param array $m
	 */
	public function set(array $m){
		for($r = 0; $r < $this->rows; ++$r){
			$this->matrix[$r] = [];
			for($c = 0; $c < $this->columns; ++$c){
				$this->matrix[$r][$c] = $m[$r][$c] ?? 0;
			}
		}
	}

	/**
	 * @return int|mixed
	 */
	public function getRows(){
		return $this->rows;
	}

	/**
	 * @return int|mixed
	 */
	public function getColumns(){
		return $this->columns;
	}

	/**
	 * @param $row
	 * @param $column
	 * @param $value
	 *
	 * @return bool
	 */
	public function setElement($row, $column, $value){
		if($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0){
			return false;
		}
		$this->matrix[(int) $row][(int) $column] = $value;

		return true;
	}

	/**
	 * @param int $row
	 * @param int $column
	 *
	 * @return float|false
	 */
	public function getElement($row, $column){
		if($row > $this->rows or $row < 0 or $column > $this->columns or $column < 0){
			return false;
		}

		return $this->matrix[(int) $row][(int) $column];
	}

	/**
	 * @return bool
	 */
	public function isSquare(){
		return $this->rows === $this->columns;
	}

	/**
	 * @return Matrix|false
	 */
	public function add(Matrix $matrix){
		if($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()){
			return false;
		}
		$result = new Matrix($this->rows, $this->columns);
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$element = $matrix->getElement($r, $c);
                assert($element !== false, "An element should never be false if the height and width are the same.");
				$result->setElement($r, $c, $this->matrix[$r][$c] + $element);
			}
		}

		return $result;
	}

	/**
	 * @return Matrix|false
	 */
	public function substract(Matrix $matrix){
		if($this->rows !== $matrix->getRows() or $this->columns !== $matrix->getColumns()){
			return false;
		}
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$element = $matrix->getElement($r, $c);
                assert($element !== false, "An element should never be false if the height and width are the same.");
				$result->setElement($r, $c, $this->matrix[$r][$c] - $element);
			}
		}

		return $result;
	}

	/**
	 * @param $number
	 *
	 * @return Matrix
	 */
	public function multiplyScalar($number){
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] * $number);
			}
		}

		return $result;
	}


	/**
	 * @param $number
	 *
	 * @return Matrix
	 */
	public function divideScalar($number){
		$result = clone $this;
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($r, $c, $this->matrix[$r][$c] / $number);
			}
		}

		return $result;
	}

	/**
	 * @return Matrix
	 */
	public function transpose(){
		$result = new Matrix($this->columns, $this->rows);
		for($r = 0; $r < $this->rows; ++$r){
			for($c = 0; $c < $this->columns; ++$c){
				$result->setElement($c, $r, $this->matrix[$r][$c]);
			}
		}

		return $result;
	}

	/**
	 * Naive Matrix product, O(n^3)
	 *
	 * @return Matrix|false
	 */
	public function product(Matrix $matrix){
		if($this->columns !== $matrix->getRows()){
			return false;
		}
		$c = $matrix->getColumns();
		$result = new Matrix($this->rows, $c);
		for($i = 0; $i < $this->rows; ++$i){
			for($j = 0; $j < $c; ++$j){
				$sum = 0;
				for($k = 0; $k < $this->columns; ++$k){
					$element = $matrix->getElement($k, $j);
                    assert($element !== false, "There must be an element here");
					$sum += $this->matrix[$i][$k] * $element;
				}
				$result->setElement($i, $j, $sum);
			}
		}

		return $result;
	}

	/**
	 * Computation of the determinant of 1x1, 2x2 and 3x3 matrices
	 *
	 * @return float|false
	 */
	public function determinant(){
		if($this->isSquare() !== true){
			return false;
		}
		switch($this->rows){
			case 1:
				return $this->matrix[0][0];
			case 2:
				return $this->matrix[0][0] * $this->matrix[1][1] - $this->matrix[0][1] * $this->matrix[1][0];
			case 3:
				return $this->matrix[0][0] * $this->matrix[1][1] * $this->matrix[2][2] + $this->matrix[0][1] * $this->matrix[1][2] * $this->matrix[2][0] + $this->matrix[0][2] * $this->matrix[1][0] * $this->matrix[2][1] - $this->matrix[2][0] * $this->matrix[1][1] * $this->matrix[0][2] - $this->matrix[2][1] * $this->matrix[1][2] * $this->matrix[0][0] - $this->matrix[2][2] * $this->matrix[1][0] * $this->matrix[0][1];
		}

		return false;
	}


	/**
	 * @return string
	 */
	public function __toString(){
		$s = "";
		for($r = 0; $r < $this->rows; ++$r){
			$s .= implode(",", $this->matrix[$r]) . ";";
		}

		return "Matrix({$this->rows}x$this->columns;" . substr($s, 0, -1) . ")";
	}

}