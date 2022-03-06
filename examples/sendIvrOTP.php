<?php
require dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

/* Send OTP by IVR, Call static */
try {
	$gsOTP = OTPHandler::ByIVR(API_KEY, MOBILE, IVR_TEMPLATE_ID);
	echo "ReferenceID: {$gsOTP}";
} catch (\Exception $e) {
	echo $e->getMessage();
}

/* Send OTP by IVR */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->sendIvr(MOBILE, TEMPLATE_ID);
	echo "ReferenceID: " . $gsOTP;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/

/* Send OTP by IVR */
/*
$gsOtp = new OTPHandler(API_KEY);
try {
	$gsOTP = $gsOtp->setMobile(MOBILE)->sendIvr();
	echo "ReferenceID: " . $gsOTP;
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
*/