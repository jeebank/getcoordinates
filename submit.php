<?php
if(isset($_POST['submit_address']))
{
  $address =$_POST['address']; 
  
  if(!empty($address)){
	  $result = "<table><tr><th>API Type</th><th>Latitude</th><th>Longitude</th></tr>";
	  $result .= getLatLongOSM($address);
	  $result .= getLatLongGoogle($address);
	  $result .= "</table>";
	  echo $result;
  }else{
	  echo "please enter valid address";
  }
}

function getLatLongOSM($address){

    // url encode the address
    $address = urlencode($address);

    $url = "https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q={$address}&format=json&limit=1";

    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $resp = json_decode($resp_json, true);
	if(!empty($resp)){
		return "<tr><td>OSM API:</td><td>".$resp[0]['lat']."</td><td>".$resp[0]['lon']."</td></tr>";
	}else{
		return false;
	}
}
function getLatLongGoogle($address){
	if(!empty($address)){
		//Formatted address
		$formattedAddr = str_replace(' ','+',$address);
		//Send request and receive json data by address
		$geocodeFromAddr = file_get_contents
		('//maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false');
		$output = json_decode($geocodeFromAddr);
		//Get latitude and longitute from json data
		$data['latitude'] = $output->results[0]->geometry->location->lat;
		$data['longitude'] = $output->results[0]->geometry->location->lng;
		//Return latitude and longitude of the given address
		if(!empty($data)){
			return "<tr><td>Google Map API:</td><td>".$data['latitude']."</td><td>".$data['longitude']."</td></tr>";
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>