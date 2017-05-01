<?php
require "genericAWS.php";

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
	$geoType = $_GET['geo'];

}else{
	
}

$table = "jsonUpload";
$result = mysqli_query($con,"SELECT json, id2 FROM $table WHERE id2 = '$uniqueID'");
while($row = mysqli_fetch_array($result)) {
	$jsonData = $row['json'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://npmcdn.com/@turf/turf@3.5.1/turf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <!-- <script src="turf-buffer-artisinal/"></script> -->

    <title>
        LA 558 Final Project
    </title>
    <style>
        html,
        body,
        #map {
            height: 520px;
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
<p>Select a GeoJSON file to upload to the map (.json and .geojson file extensions are accepted). If you don't have any GeoJSON data to upload, click <a href="http://indicator.extension.iastate.edu/z_bh/LA558_FinalProject/index.php?id=5904a9fe11f76&geo=Point"> here </a> for points, <a href="http://indicator.extension.iastate.edu/z_bh/LA558_FinalProject/index.php?id=59060f8740ff7&geo=Polygon"> here </a> for polygons, and <a href="http://indicator.extension.iastate.edu/z_bh/LA558_FinalProject/index.php?id=59062a31aacc7&geo=LineString"> here </a> for lines. <a  href="LA558_FinalProject.pdf"> Final Project Presentation </a></p>
<!-- works for chrome not safari -->

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileUpload" id="fileUpload" accept=".json,.geojson">
    <input type="submit" name="submit">
</form>
<button name="center" onclick="center()">Add Center Point</button>
<button name="removeCenter" onclick="removeCenter()" style="display:none">Remove Center Point</button>

<button name="convex" onclick="convex()">Create Polygon</button>
<button name="removeConvex" onclick="removeConvex()" style="display:none">Remove Polygon</button>

<button name="simplify" onclick="simplify()">Add Simplified Polygon</button>
<button name="removeSimplify" onclick="removeSimplify()" style="display:none">Remove Simplified Polygon</button>

<button name="area" onclick="calcArea()">Calculate Area</button>
<button name="length" onclick="calcLength()">Calculate Length</button>
<br>
<div id="map"></div>

<script type="text/javascript">
    var map = L.map('map').setView([41.960638, -93.637359], 7);

    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18
    }).addTo(map);

    var jsonData = <?php echo $jsonData; ?>;

    // json_encode() used to produce string results from php variable
    var geoType = <?php echo json_encode($geoType); ?>;
    

    // Turf Center - to adjust the center of the map view
    var centerPt = turf.center(jsonData);

    var centerPtView2 = centerPt.geometry.coordinates[0]
    var centerPtView1 = centerPt.geometry.coordinates[1]

    map.setView([centerPtView1, centerPtView2]);

    var geojsonMarkerOptions = {
        radius: 8,
        fillColor: "#f03b20",
        color: "#bd0026",
        title: "This is the absolute center of the points", // isn't working
        weight: 1,
        opacity: 1,
        fillOpacity: 0.8
    };

    var centerPtLayer = L.geoJson(centerPt, {
        pointToLayer: function(feature, latlng) {
            return L.circleMarker(latlng, geojsonMarkerOptions);
        }
    });

    function center() {
        // Turf Center - adds point at the absolute center of upload file
        $('[name="center"]').hide()
        $('[name="removeCenter"]').show();

        centerPtLayer.addTo(map);
    }

    function removeCenter() {
        $('[name="center"]').show()
        $('[name="removeCenter"]').hide();

        map.removeLayer(centerPtLayer);
    }

    function convex() {
        //Turf Convex -- creates a polygon by connecting all pts/lines
        $('[name="convex"]').hide()
        $('[name="removeConvex"]').show();

        hullLayer.addTo(map);
    }

    function removeConvex() {
        $('[name="convex"]').show()
        $('[name="removeConvex"]').hide();

        map.removeLayer(hullLayer);
    }

    function calcArea() {
        //Turf Area -- calculate total sq area of polygons
        var area = turf.area(jsonData) * 0.000000386102158542;
        var area = area.toLocaleString('en-US', {
            minimumFractionDigits: 2
        });
        alert(area + " total square miles");
    };

    function calcLength() {
        //Turf lineDistance -- calculate length of lines
        var length = turf.lineDistance(jsonData, 'miles');
		var length = length.toLocaleString('en-US', {
            minimumFractionDigits: 2
        });
        alert(length + " total miles");
    };

    function simplify() {

        simplifiedLayer.addTo(map);

        $('[name="simplify"]').hide()
        $('[name="removeSimplify"]').show();
    };

    function removeSimplify() {

        map.removeLayer(simplifiedLayer);

        $('[name="simplify"]').show()
        $('[name="removeSimplify"]').hide();
    };

    if (geoType == "Point") {
        var uploadedJSONMarkers = L.geoJson(jsonData).addTo(map);

        var hull = turf.convex(jsonData);
        var hullLayer = L.geoJson(hull);

        $('[name="length"]').hide();
        $('[name="area"]').hide();
        $('[name="simplify"]').hide()

    } else if (geoType == "Polygon") {
        var uploadedJSONPolygon = L.geoJson(jsonData).addTo(map);

        var simplified = turf.simplify(jsonData, 0.25, false);
        var simplifiedLayer = L.geoJson(simplified);

        $('[name="convex"]').hide();
        $('[name="area"]').show();
        $('[name="simplify"]').show();


    } else if (geoType == "LineString") {
        var uploadedJSONLine = L.geoJson(jsonData).addTo(map);

        var hull = turf.convex(jsonData);
        var hullLayer = L.geoJson(hull);

        $('[name="area"]').hide();
        $('[name="simplify"]').hide();
        $('[name="length"]').show();
    };

    /* ISSUE -- Buffer only works on one pt - needs to work on multiple pts

    // geojson coordinates to js array for buffer
    var ptCoordinates = jsonData.features.map(function (el) {
      return el.geometry.coordinates;
    });

    // pt coordinates for single geojson object
    //var ptCoordinates = jsonData.features[0].geometry.coordinates;

    var pt1 = turf.point (ptCoordinates);
    console.log(pt1);
    var buffer = turf.buffer(pt1, 30, 'miles');  
    	
    //code fix for oval buffer with turf	
    var bpathother=[];
    var resolution = 1000;
     var ring = [];
      var resMultiple = 360/resolution;
      for(var i  = 0; i < resolution; i++) {
        var spoke = turf.destination(pt1, 30, i*resMultiple, 'miles');
    	var coord = [parseFloat(spoke.geometry.coordinates[0]), parseFloat(spoke.geometry.coordinates[1])];
        bpathother.push(coord);
        ring.push(spoke.geometry.coordinates);
      }
      if((ring[0][0] !== ring[ring.length-1][0]) && (ring[0][1] != ring[ring.length-1][1])) {
      var coords = [parseFloat(ring[0][0]), parseFloat(ring[0][1])];
        bpathother.push(coords);
        ring.push([ring[0][0], ring[0][1]]);
      }
      
    var poly1 = turf.polygon([bpathother]);
    var feature_collection2 = turf.featureCollection([poly1]);
    L.geoJson(feature_collection2).addTo(map);
    */
</script>

</html>