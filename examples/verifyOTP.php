<?php
require dirname(__FILE__) . '/../vendor/autoload.php';

use GlobalSmartOtp\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$otp = 0;

$gsOtp = new OTPHandler($apiKey);

// Verify OTP
$verified = $gsOtp->verify($mobile, $otp);
if (!$verified) {
    echo "Error code: " . $gsOtp->getErrorCode() . PHP_EOL;
    echo "Error message" . $gsOtp->getErrorMessage() . PHP_EOL;
} else {
    echo "Verified OTP!";
}
