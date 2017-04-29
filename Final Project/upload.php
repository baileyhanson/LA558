<?php
require "genericAWS.php";

// SLOW INTERNET WAS CAUSING TIMEOUT ISSUE
//ini_set('MAX_EXECUTION_TIME', 3600);

$database = "userSpatialData";
$con=mysqli_connect($hostname, $username, $password, $database);

$target_file = "uploads/";
$target_path = $target_file . basename($_FILES['fileUpload']['name']);

move_uploaded_file($_FILES['fileUpload']['tmp_name'], 'uploads/uploadFile.json');
//error_reporting(E_ALL);

//echo($target_path);

//read the json file contents
$jsondata = file_get_contents('uploads/uploadFile.json');

$uniqueID = uniqid();

//echo($jsondata);

$table = "jsonUpload";
//insert into mysql table
$result = mysqli_query($con,"INSERT INTO $table(json,id2) VALUES('$jsondata','$uniqueID')");

if($result)
{
//echo "Upload successful";
header('Location: index.php?id='.$uniqueID);  // need to pass an id to the index.php file that can use that to query db for file
}
else
{
echo "Upload error";

}

mysqli_close($con);

?>