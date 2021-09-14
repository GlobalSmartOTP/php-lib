<?php
require dirname(__FILE__) . '/../vendor/autoload.php';

use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$referenceID = ""; // Get referenceID from gsOtp->send()

$gsOtp = new OTPHandler($apiKey);

// Check OTP status
$status = $gsOtp->status($referenceID);
if (!$status) {
    echo "Error code: " . $gsOtp->getErrorCode() . PHP_EOL;
    echo "Error message" . $gsOtp->getErrorMessage() . PHP_EOL;
} else {
    foreach ($status as $key => $value) {
        echo "{$key}: {$value}" . PHP_EOL;
    }
}
