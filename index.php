<?php
interface IDataSet {
	public function getRowCount();
	public function getValueAt($row, $column);
}

class InMemoryDataSet implements IDataSet {
	private $_data = array();

	public function __construct(array $data) {
		$this->_data = $data;
	}

	public function getName(){
		return $this->_data[0];
	}

	public function getRowCount() {
		return count($this->_data);
	}



	public function getValueAt($row, $column) {
		if ($row >= $this->getRowCount()) {
			throw new OutOfRangeException();
		}

		return isset($this->_data[$row][$column])
		? $this->_data[$row][$column]
		: null;
	}
}

function CSVToDataSet($file) {
	return new InMemoryDataSet(array_map('str_getcsv', file($file)));
}

// class DataQuery {
// 	private $_dataSet;

// 	public function __construct(IDataSet $dataSet) {
// 		$this->_dataSet = $dataSet;

// 	}

// 	public function getRowsWithDuplicates($columnIndex) {
// 		$values = array();
// 		for ($i = 0; $i < $this->_dataSet->getRowCount(); ++$i) {
// 			$values[$this->_dataSet->getValueAt($i, $columnIndex)][] = $i;
// 		}

// 		return array_filter($values, function($row) { return count($row) > 1; });
// 	}
// }

/**
*  
*/
class Merge 
{
	private $obj_a;
	private $obj_b;
	
	public function __construct($obj_a, $obj_b)
	{
		$this->obj_a = $obj_a;
		$this->obj_b = $obj_b;
	}

	public function getName()
	{
		return $this->obj_a->getName();
		//var_dump($output);die('here');
	}

	public function merge($a, $b)
	{
		var_dump($a);
		$keys = array_keys($a);
		foreach ($keys as $key) {
			$final[] = array_merge($a[$key], $b[$key]);
			# code...
		}

		// // echo "<pre>";
		// // var_dump($this->obj_a);
		// // var_dump($this->obj_b);
		// $merged = array_merge($a, $b);

		 echo "<pre>";
		 var_dump($final, 'have fun');die;
	}



}

$awardsCSV = (array)CSVToDataSet("awards.csv");
$contractsCSV = (array)CSVToDataSet("contracts.csv");

// $awards = new DataQuery($awardsCSV);
// $contracts = new DataQuery($contractsCSV);
$output = new Merge($awardsCSV, $contractsCSV);
$dupes = $output->merge($awardsCSV, $contractsCSV);

var_dump($dupes);