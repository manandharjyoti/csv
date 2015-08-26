<?php

class CSVArray {
	private $_data = array();

	public function __construct(array $data) {
		// //makes the first row of csv as index of array
		$header = array_shift($data);
		$csv = array();
		foreach ($data as $row) {
			$csv[] = array_combine($header, $row);
		}

		$this->_data = $csv;
	}

	public function getTotalAmount(){
		$amount = 0;
		foreach ($this->_data as $key => $value) {
			if($this->_data[$key]['status'] == "Closed")
				$amount += $this->_data[$key]['Amount'];
			# code...
		}
		return $amount;
	}

	

	public function changeKey(){
		$result = array();
		foreach($this->_data as $key=>$value)
		{
			$result[$value['contractname']] = $value;
		}

		return $result;
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

	private function geoLocation($address)
	{
		$geocode=file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false");

		$geo = '';
		$output= (array)json_decode($geocode);


		if($output['results']){
			$lat = $output['results'][0]->geometry->location->lat;
			$lng = $output['results'][0]->geometry->location->lng;
			$geo = $lat.",".$lng;
		}
		
		return $geo;
	}

	public function merge()
	{
		$final = (array_merge($this->awards, $this->contracts));



		foreach($final as $key=>$item)
		{	
			$k = $key;
			$final[$key]['contractDate'] = (isset($this->awards[$key]['contractDate']))?$this->awards[$key]['contractDate']:'';
			$final[$key]['completionDate'] = (isset($this->awards[$key]['completionDate']))?$this->awards[$key]['completionDate']:'';
			$final[$key]['awardee'] = (isset($this->awards[$key]['awardee']))?$this->awards[$key]['awardee']:'';
			$final[$key]['awardeeLocation'] = (isset($this->awards[$key]['awardeeLocation']))?$this->awards[$key]['awardeeLocation']:'';
			$final[$key]['latlon'] = $this->geoLocation($final[$key]['awardeeLocation']);
			$final[$key]['Amount'] = (isset($this->awards[$key]['Amount']))?$this->awards[$key]['Amount']:'';
		}

		$fp = fopen('file.csv', 'w');

		fputcsv($fp, array_keys($final[$k]));//heder

		foreach ($final as $fields) {
		 	# code...
			fputcsv($fp, $fields);
		}
		fclose($fp);

		return 'file.csv';

	}
}


function CSVToArray($file) {
	return new CSVArray(array_map('str_getcsv', file($file)));
}

$awardsCSV = CSVToArray("awards.csv");
$contractsCSV = CSVToArray("contracts.csv");

$awards = $awardsCSV->changeKey();

$contracts = $contractsCSV->changeKey();

unset($awardsCSV);
unset($contractsCSV);

$output = new Merge($awards, $contracts);

$file = $output->merge();
$finalCSV = CSVToArray($file);

echo "Total Amount of Closed Contracts: ",$finalCSV->getTotalAmount();




