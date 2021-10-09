<?php
include("../src/ChatTokenBuilder2.php");

$appId = $_GET['appId'];// "970CA35de60c44645bbae8a215061b33";
$appCertificate = $_GET['appCertificate'];//"5CFd2fd1755d40ecb72977518be15d3b";
$user = $_GET['user'];//"2882341273";
$expireTimeInSeconds = 3600;

$data = [];
$token = ChatTokenBuilder2::buildUserToken($appId, $appCertificate, $user, $expireTimeInSeconds);
//$data[] = json_decode($token);
echo 'Chat user token: ' . $token . PHP_EOL;

$token = ChatTokenBuilder2::buildAppToken($appId, $appCertificate, $expireTimeInSeconds);
//$data[] = json_decode($token);
echo 'Chat app token: ' . $token . PHP_EOL;
?>
