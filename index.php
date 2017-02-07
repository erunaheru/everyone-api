<!DOCTYPE html>
<html>
<head>
	<title>Level 8 Technology</title>
	<link rel="stylesheet" href="./main.css" type="text/css">
</head>
<body><div class='container'><div class='centerblock' style='width: 800px;'>

<h1>Everyone API Lookup</h1>

<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$number = $_POST[number];
	$url = "http://api.everyoneapi.com/v1/phone/+1".$number."?account_sid=".$sid."&auth_token=".$auth."&pretty=true";
	$jsondata = http_parse_message(http_get($url, array(),$httpinfo))->body;
	echo "<b>Number:</b> ".$_POST['number']."<br><br>";
	if ($httpinfo[response_code] == 200) {
		if (strpos($jsondata, '"status": true') !== FALSE) {
			$data = json_decode($jsondata);
//			echo "<pre>";
//			print_r($data);
//			echo "</pre>";
			if ($data->data->name) {echo "<b>Name: </b>".$data->data->name."<br>";};
			if ($data->data->cnam) {echo "<b>CNAM: </b>".$data->data->cnam."<br>";};
			if ($data->data->gender) {echo "<b>Gender: </b>".$data->data->gender."<br>";};
			if ($data->type) {echo "<b>Type: </b>".$data->type."<br>";};
			if ($data->data->carrier->id) {
				echo "<b>Carrier of Record: </b>".$data->data->carrier->name." (ID: ".$data->data->carrier->id.")<br>";
				if ($data->data->carrier_o->id) {
					if ($data->data->carrier->id != $data->data->carrier_o->id ){
						echo "<b>Original Carrier: </b>".$data->data->carrier_o->name." (ID: ".$data->data->carrier_o->id.")<br>";
					}
				}
			}
			if ($data->data->linetype) {echo "<b>Line Type: </b>".$data->data->linetype."<br>";};
			if ($data->data->address) {echo "<b>Address: </b>".$data->data->address."<br>";};
			if ($data->data->location->city) {echo "<b>City: </b>".$data->data->location->city."<br>";};
			if ($data->data->location->state) {echo "<b>State: </b>".$data->data->location->state."<br>";};
			if ($data->data->location->zip) {echo "<b>Zip: </b>".$data->data->location->zip."<br>";};
			if ($data->data->location->geo->latitude) {echo "<b>Map:</b> <a href='https://www.google.com/maps/preview?q=".$data->data->location->geo->latitude.",".$data->data->location->geo->longitude."'>Google Maps</a><br>";};
			if ($data->data->image) {echo "<b>Image:</b> <a href='".$data->data->image->large."'><img src='".$data->data->image->small."'></a><br>";};
			echo "<hr>";
			if ($data->pricing->total) {
				$cost = round(abs($data->pricing->total),4);
				echo "This lookup cost $cost cents";
			}
		} else {
			echo "Error processing response. Response dump: <br>";
			echo "<pre>";
			echo $jsondata;
			echo "</pre>";
		}
	} elseif ($httpinfo[response_code] == 400) {
		echo "Invalid Number, $number";
	} elseif ($httpinfo[response_code] == 401) {
		echo "Invalid API credentials";
	} elseif ($httpinfo[response_code] == 402) {
		echo "Your account balance is too low to complete this request";
	} elseif ($httpinfo[response_code] == 403) {
		echo "Your account has been rate limited for suspected malicious activity";
	} elseif ($httpinfo[response_code] == 404) {
		echo "Number not found, $number";
	} else {
		echo "GET error. Response dump: <br>";
		echo "<pre>";
		print_r($httpinfo);
		echo "</pre>";
	}
	echo "<hr>";
}
?>

<form action='/everyone-api/' method='post'>
10 digit phone number: <input name='number' type='text'>
<input type='submit' value='Look up' >
<br>
</form>
</div></div></body></html>
