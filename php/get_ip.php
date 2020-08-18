<?php
header("Access-Control-Allow-Origin: *");


$data = [];

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $data['ip'] = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $data['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

$data['ip'] = $_SERVER['REMOTE_ADDR'];

echo json_encode($data);
