<?php
require dirname(__FILE__) . '/../src/OTPHandler.php';
require_once dirname(__FILE__) . '/configs.php';

use GlobalSmartOTP\Api\OTPHandler;

$gsOtp = new OTPHandler(API_KEY);

/*************** Send OTP by SMS ***************/
//try {
//	$gsOTP = $gsOtp->sendSMS(MOBILE, TEMPLATE_ID);
//	echo "ReferenceID: " . $gsOTP;
//} catch (Exception $e) {
//	echo "Error: " . $e->getMessage();
//}


/*************** Send OTP by SMS with Params ***************/
//try {
//	$gsOTP = $gsOtp->setParam1("مقدار یک")->setParam2("مقدار دوم")->sendSMS(MOBILE, TEMPLATE_ID);
//	echo "ReferenceID: " . $gsOTP;
//} catch (Exception $e) {
//	echo "Error: " . $e->getMessage();
//}


/*************** Send OTP by Whatsapp Messenger ***************/
//try {
//	$gsOTP = $gsOtp->setProvider('whatsapp')->sendMessenger(MOBILE, TEMPLATE_ID);
//	echo "ReferenceID: " . $gsOTP;
//} catch (Exception $e) {
//	echo "Error: " . $e->getMessage();
//}

/*************** Send OTP by Gap Messenger ***************/
//try {
//	$gsOTP = $gsOtp->setProvider('gap')->sendMessenger(MOBILE, TEMPLATE_ID);
//	echo "ReferenceID: " . $gsOTP;
//} catch (Exception $e) {
//	echo "Error: " . $e->getMessage();
//}