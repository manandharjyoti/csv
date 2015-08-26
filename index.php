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
		$final_array = array();
		$array_item = array();
		foreach($this->contracts as $key=>$c_item)
		{	
			if(isset($this->awards[$key]))
			{
				$array_item['contractname'] = $c_item['contractname'];
				$array_item['status'] = $c_item['status'];
				$array_item['bidPurchaseDeadline'] = $c_item['bidPurchaseDeadline'];
				$array_item['bidSubmissionDeadline'] = $c_item['bidSubmissionDeadline'];
				$array_item['bidOpeningDate'] = $c_item['bidOpeningDate'];
				$array_item['tenderid'] = $c_item['tenderid'];
				$array_item['publicationDate'] = $c_item['publicationDate'];
				$array_item['publishedIn'] = $c_item['publishedIn'];


				$array_item['contractDate'] = $this->awards[$key]['contractDate'];
				$array_item['completionDate'] = $this->awards[$key]['completionDate'];
				$array_item['awardee'] = $this->awards[$key]['awardee'];
				$array_item['awardeeLocation'] = $this->awards[$key]['awardeeLocation'];
				$array_item['Amount'] = $this->awards[$key]['Amount'];
			}
			else
			{
				$array_item['contractname'] = $c_item['contractname'];
				$array_item['status'] = $c_item['status'];
				$array_item['bidPurchaseDeadline'] = $c_item['bidPurchaseDeadline'];
				$array_item['bidSubmissionDeadline'] = $c_item['bidSubmissionDeadline'];
				$array_item['bidOpeningDate'] = $c_item['bidOpeningDate'];
				$array_item['tenderid'] = $c_item['tenderid'];
				$array_item['publicationDate'] = $c_item['publicationDate'];
				$array_item['publishedIn'] = $c_item['publishedIn'];


				$array_item['contractDate'] = '';
				$array_item['completionDate'] = '';
				$array_item['awardee'] = '';
				$array_item['awardeeLocation'] = '';
				$array_item['Amount'] = '';
			}
			array_push($final_array, $array_item);
		}
		//var_dump($final_array);die;
		
		//$final = array_intersect($this->awards, $this->contracts);
		
		$fp = fopen('file.csv', 'w');
		fputcsv($fp, array_keys($final_array[0]));

		foreach ($final_array as $fields) {
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
