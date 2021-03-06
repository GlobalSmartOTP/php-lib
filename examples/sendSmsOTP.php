<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

/* Send OTP by SMS, Call static */
try {
	$gsOTP = OTPHandler::BySms(API_KEY, MOBILE, TEMPLATE_ID);
	echo "ReferenceID: {$gsOTP}";
} catch (\Exception $e) {
	echo $e->getMessage();
}

/* Send OTP by SMS */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->sendSMS(MOBILE, TEMPLATE_ID);
	echo "ReferenceID: " . $gsOTP;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/

/* Send OTP by SMS with params */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->setMobile(MOBILE)
		->setTemplateID(6)
		->setParam1("sample param 1")
		->setParam2('sample param 2')
		->sendSms();
	echo "ReferenceID: " . $gsOTP;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/