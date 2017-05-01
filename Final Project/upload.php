<?php
require "genericAWS.php";

$database = "userSpatialData";
$con=mysqli_connect($hostname, $username, $password, $database);

$target_file = "uploads/";
$target_path = $target_file . basename($_FILES['fileUpload']['name']);

move_uploaded_file($_FILES['fileUpload']['tmp_name'], 'uploads/uploadFile.json');

$uniqueID = uniqid();

//read the json file contents
$jsondata = file_get_contents('uploads/uploadFile.json');

$json = json_decode($jsondata, true);
$featureType = $json['features'][0]['geometry']['type'];

$table = "jsonUpload";
//insert into mysql table
$result = mysqli_query($con,"INSERT INTO $table(json,id2,geoType) VALUES('$jsondata','$uniqueID','$featureType')");

if($result)
{
//echo "Upload successful";
header('Location: index.php?id='.$uniqueID.'&geo='.$featureType);
} else {
echo "Upload error";
}

mysqli_close($con);

?>