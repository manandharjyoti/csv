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

class DataQuery {
	private $_dataSet;

	public function __construct(IDataSet $dataSet) {
		$this->_dataSet = $dataSet;

	}

	public function getRowsWithDuplicates($columnIndex) {
        $values = array();
        for ($i = 0; $i < $this->_dataSet->getRowCount(); ++$i) {
            $values[$this->_dataSet->getValueAt($i, $columnIndex)][] = $i;
        }

        return array_filter($values, function($row) { return count($row) > 1; });
	}
}

$dataSet = CSVToDataSet("awards.csv");
$query = new DataQuery($dataSet);
$dupes = $query->getRowsWithDuplicates(0);
echo"<pre>";
var_dump($query);