<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

/* Send OTP by SMS with params */
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->setMobile(MOBILE)
		->setTemplateID(11)
		->setParam1("#param1_value")
		->setParam2("gsOTP .com")
		->sendSms();
	echo "ReferenceID: " . $gsOTP;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}