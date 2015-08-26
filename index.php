<?php

class CSVArray {
	private $_data = array();

	public function __construct(array $data) {
		//makes the first row of csv as index of array
		$header = array_shift($data);
		$csv = array();
		foreach ($data as $row) {
			$csv[] = array_combine($header, $row);
		}

		$this->_data = $csv;
	}

	public function getData(){
		return $this->_data;
	}
}

function CSVToArray($file) {
	return new CSVArray(array_map('str_getcsv', file($file)));
}

$awardsCSV = CSVToArray("awards.csv");
$contractsCSV = CSVToArray("contracts.csv");

/**
*  
*/
class Merge 
{
	private $awards;
	private $contracts;
	
	public function __construct($awards, $contracts)
	{
		$this->awards = $awards;
		$this->contracts = $contracts;
	}

	public function merge()
	{
		$final = array_merge($this->awards, $this->contracts);
		
		$fp = fopen('file.csv', 'w');
		fputcsv($fp, array_keys($final[4]));

		foreach ($final as $fields) {
		 	# code...
			fputcsv($fp, $fields);
		}
		fclose($fp);

		echo "<pre>";
		var_dump($final, 'have fun');
	}
}


$output = new Merge($awardsCSV->getData(), $contractsCSV->getData());

$dupes = $output->merge($awardsCSV->getData(), $contractsCSV->getData());
