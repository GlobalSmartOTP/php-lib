<?php
require dirname(__FILE__) . '/../vendor/autoload.php';

use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;

$gsOtp = new OTPHandler($apiKey);

// Send IVR OTP
$referenceID = $gsOtp->sendIVR($mobile, $templateID);
if (!$referenceID) {
    echo "Error code: " . $gsOtp->getErrorCode() . PHP_EOL;
    echo "Error message" . $gsOtp->getErrorMessage() . PHP_EOL;
} else {
    echo "ReferenceID: {$referenceID}";
}
