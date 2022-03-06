<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

/* Verify OTP Call static */
try {
	$gsOTP = OTPHandler::isVerify(API_KEY, MOBILE, OTP);
	echo "OTP is verified!";
} catch (\Exception $e) {
	echo $e->getMessage();
}

/* Verify OTP */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$verified = $gsOtp->verify(MOBILE, OTP);
	echo "Verified OTP!";
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/

/* Verify OTP */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$verified = $gsOtp->setMobile(MOBILE)
		->setOTP(OTP)
		->verify();
	echo "Verified OTP!";
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/