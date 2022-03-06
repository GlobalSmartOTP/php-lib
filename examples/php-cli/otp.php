#!/usr/bin/php -q
<?php
if (PHP_SAPI !== 'cli') {
	echo "Please run in command line!";
	exit();
}

require dirname(__FILE__) . '/../../vendor/autoload.php';

use GlobalSmartOTP\Api\OTPHandler;

do {
	$apiKey = getInput("Please Enter ApiKey:");
} while (empty($apiKey));

do {
	$mobile = getInput("Please Enter Mobile Number:");
} while (empty($mobile));

do {
	$method = (int)getInput("Please choice send method:" . PHP_EOL
		. "1) sms" . PHP_EOL
		. "2) messenger" . PHP_EOL
		. "3) ivr");
} while (!in_array($method, [1, 2, 3]));

$templateID = 3;
switch ($method) {
	case 2:
		$sendMethod = "ByMessenger";
		break;
	case 3:
		$sendMethod = "ByIvr";
		$templateID = 2;
		break;
	default:
		$sendMethod = "BySms";
		break;
}

try {
	$gsOTP = OTPHandler::{$sendMethod}($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$gsOTP}" . PHP_EOL;
} catch (\Exception $e) {
	echo $e->getMessage();
	exit();
}

do {
	do {
		$otp = getInput("Please Enter Received OTP:");
	} while (empty($otp));

	try {
		$gsOTP = OTPHandler::isVerify($apiKey, $mobile, $otp);
		echo "OTP verified!";
		exit;
	} catch (\Exception $e) {
		echo $e->getMessage() . PHP_EOL;
		$otp = "";
	}
} while (empty($otp));

function getInput($msg): string
{
	echo $msg . PHP_EOL;
	$handle = fopen("php://stdin", "r");
	return trim(str_replace("\r\n", "", fgets($handle)));
}

?>
