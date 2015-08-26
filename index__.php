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
		echo '<pre>';
		$final = (array_merge($this->awards, $this->contracts));
		foreach($final as $key=>$item)
		{
			$final[$key]['contractDate'] = (isset($this->awards[$key]['contractDate']))?$this->awards[$key]['contractDate']:'';
			$final[$key]['completionDate'] = (isset($this->awards[$key]['completionDate']))?$this->awards[$key]['completionDate']:'';
			$final[$key]['awardee'] = (isset($this->awards[$key]['awardee']))?$this->awards[$key]['awardee']:'';
			$final[$key]['awardeeLocation'] = (isset($this->awards[$key]['awardeeLocation']))?$this->awards[$key]['awardeeLocation']:'';
			$final[$key]['Amount'] = (isset($this->awards[$key]['Amount']))?$this->awards[$key]['Amount']:'';
		}
		
		$fp = fopen('file.csv', 'w');
		//fputcsv($fp, array_keys($final_array[0]));

		foreach ($final as $fields) {
		 	# code...
			fputcsv($fp, $fields);
		}
		fclose($fp);

		echo "<pre>";
		var_dump($final, 'have fun');
	}
}


function CSVToArray($file) {
	return new CSVArray(array_map('str_getcsv', file($file)));
}
$awardsCSV = CSVToArray("awards.csv");
$contractsCSV = CSVToArray("contracts.csv");

$awards = array();
$contracts = array();
foreach($awardsCSV->getData() as $key=>$value)
{
	$awards[$value['contractname']] = $value;
}

foreach($contractsCSV->getData() as $key=>$value)
{
	$contracts[$value['contractname']] = $value;
}

unset($awardsCSV);
unset($contractsCSV);

$output = new Merge($awards, $contracts);

$dupes = $output->merge();
