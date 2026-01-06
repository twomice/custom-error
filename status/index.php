<?php

// Bitwise error-state flags:
// NOTE: This script MAY NOT report an error code of "1"; it is reserved
// for other processes (namely bash scripts which may process this output).
define('HEALTH_STATUS_PROBLEM_FILE', 2);
define('HEALTH_STATUS_PROBLEM_DB', 4);

$configFile = __DIR__ . '/config.php';
$configBaseName = basename($configFile);
if(
  file_exists(stream_resolve_include_path($configFile))
  && is_readable($configFile)
){
  include $configFile;
}
else {
  die('Config file '. $configBaseName .' not found or unreadable.');
}

if (
  empty($servername)
  || empty($username)
  || empty($password)
) {
  die('Configuration incomplete. Please check ' . $configBaseName);
}

$dbStatus = "";
$fileStatus = "";
$statusCode = 0;

// Test dbStatus:
// Create connection
$conn = new mysqli($servername, $username, $password);
$dbError = '';
// Check connection
if ($conn->connect_error) {
  $dbStatus = 'FAIL';
  $dbError = $conn->connect_error;
  $statusCode += HEALTH_STATUS_PROBLEM_DB;
}
else {
  $dbStatus = 'SUCCESS';
}

// Test fileStatus
$docroot = $_SERVER["DOCUMENT_ROOT"];
$testFile = "$docroot/JOINERY_DO_NOT_DELETE.txt";
$uniq = uniqid();
$fp = fopen($testFile, 'w');
fputs($fp, $uniq);
fclose($fp);
$fileContents = file_get_contents($testFile);
if ($fileContents == $uniq) {
  $fileStatus = 'SUCCESS';
}
else {
  $fileStatus = "FAIL";
  $statusCode += HEALTH_STATUS_PROBLEM_FILE;
}
$fp = fopen($testFile, 'w');
fputs($fp, 'This file is used by Joinery for system testing. Do not delete this file.');
fclose($fp);

if ($_GET['format'] == 'code') {
  echo "$statusCode";
}
else {
  $statusMessage = "dbStatus:$dbStatus; fileStatus:$fileStatus";
  echo $statusMessage;
}
