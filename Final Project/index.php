<?php
require "genericAWS.php";

// SLOW INTERNET WAS CAUSING TIMEOUT ISSUE
//ini_set('MAX_EXECUTION_TIME', 3600);

$database = "userSpatialData";
$con=mysqli_connect($hostname, $username, $password, $database);


error_reporting(0);

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if (isset($_GET['id'])){
	$uniqueID = $_GET['id'];

}else{
	
}

// Query goes here
$table = "jsonUpload";
$result = mysqli_query($con,"SELECT json, id2 FROM $table WHERE id2 = '$uniqueID'");
while($row = mysqli_fetch_array($result)) {
	$jsonData = $row['json'];
}

//echo $jsonData;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
    
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    
    <title>
       LA 558 Final Project
    </title>
    <style>
        html,
        body,
        #map {
            height: 100%;
            margin: 0px;
        }
				
		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			overflow: hidden;
			background-color: #f1f1f1;
		}
		
		li {
			float: left;
		}
		
		li a {
			display: block;
			color: #000;
			padding: 8px 16px;
			text-decoration: none;
		}

		/* Change the link color on hover */
		li a:hover {
			background-color: #555;
			color: white;
		}
		p {
			padding: 5px;
			font-size: 14px;
		}
    </style>
</head>
<ul>
  	<li><a href="https://baileyhanson.github.io/LA558/">Home</a></li>
</ul>
<p>Select a GeoJSON file to upload to the map (.json and .geojson file extensions are accepted).</p>
<!-- works for chrome not safari -->

<!-- <form action="javascript:ajaxSubmit();">  -->
<form action="upload.php" method="post" enctype="multipart/form-data">
  <input type="file" name="fileUpload" id="fileUpload" accept=".json,.geojson">
  <input type="submit" name="submit">
</form>
<br>
<div id="map"></div>

<script type="text/javascript">
       
var map = L.map('map').setView([41.960638, -93.637359], 7);

// add an OpenStreetMap tile layer
L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18
}).addTo(map);

//var jsonData = {"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-94.185791015625,42.61779143282346]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-91.49414062499999,42.85180609584705]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-92.48291015625,42.04929263868686]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-94.295654296875,41.03793062246529]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[-95.60302734375,42.04113400940807]}}]};

var jsonData = <?php print $jsonData; ?>;

// console.log type -- if point then markers, if line then line style, if polygon then polygon style
console.log("The uploaded geojson is " + jsonData);

var uploadedJSONMarkers = L.geoJson(jsonData).addTo(map);//, {

/*
$('#geocode').click(function () {
    myAddress = encodeURI($('#myAddress').val());

    var geocodingAPI_URL = "http://maps.googleapis.com/maps/api/geocode/json?address=" + myAddress + "&sensor=true";

    //Start Geocoding
    $.getJSON(geocodingAPI_URL, function (json) {
        var address = json.results[0].formatted_address;
        var lat = json.results[0].geometry.location.lat;
        var long = json.results[0].geometry.location.lng;
        var county = '';
        $.each(json.results[0].address_components, function (i, jsonData) {
            level = jsonData.types[0];
            if ('administrative_area_level_2' === level.toLowerCase()) {
                county = (jsonData.short_name);
            }
        });
		$.each(json.results[0].address_components, function (i, jsonData) {
            level = jsonData.types[0];
            if ('administrative_area_level_3' === level.toLowerCase()) {
                township = (jsonData.short_name);
            }
        });
     
        //now add the marker to the map
        var marker = L.marker([lat, long], {
            draggable: false,
            title: address,
            opacity: 1
        }).addTo(map);

        marker.bindPopup(county+"<br>"+lat+", "+long+"<br>"+township+" Township").openPopup();
		
		map.setView([lat, long]);


    });
}); //end geocode click function

*/
</script>
    
</html>