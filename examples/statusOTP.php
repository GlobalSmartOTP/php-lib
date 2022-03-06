<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

/* Check Status OTP Call static */
try {
	$gsOTP = OTPHandler::checkStatus(API_KEY, REFERENCE_ID);
	echo "OTPStatus: " . $gsOTP->getOTPStatus() . PHP_EOL;
	echo "OTPVerified:" . $gsOTP->isOTPVerified() . PHP_EOL;
	echo "OTPMethod: " . $gsOTP->getOTPMethod() . PHP_EOL;
} catch (\Exception $e) {
	echo "Error:" . $e->getMessage();
}

/* Verify OTP */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->status(REFERENCE_ID);
	echo "OTPStatus: " . $gsOTP->getOTPStatus() . PHP_EOL;
	echo "OTPVerified:" . $gsOTP->isOTPVerified() . PHP_EOL;
	echo "OTPMethod: " . $gsOTP->getOTPMethod() . PHP_EOL;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/

/* Verify OTP */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->setOTPReferenceID(REFERENCE_ID)->status();
	echo "OTPStatus: " . $gsOTP->getOTPStatus() . PHP_EOL;
	echo "OTPVerified:" . $gsOTP->isOTPVerified() . PHP_EOL;
	echo "OTPMethod: " . $gsOTP->getOTPMethod() . PHP_EOL;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/
