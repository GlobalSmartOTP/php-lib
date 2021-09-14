![gsOTP](examples/assets/logo.png) 

# GlobalSmartOTP PHP SDK

A PHP SDK for the GlobalSmartOTP API.

## Requirements

- PHP 7.4 or higher
- cURL

## Installation

```sh
$ git clone git@github.com:GlobalSmartOTP/php-lib.git
$ cd php-lib/
$ composer dumpautoload
```

## Send SMS OTP
```php
require dirname(__FILE__) . '/../vendor/autoload.php';
use GlobalSmartOTP\Api\OTPHandler;
// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
$gsOtp = new OTPHandler($apiKey);
// Send SMS OTP
$referenceID = $gsOtp->sendSMS($mobile, $templateID);
if (!$referenceID) {
    echo "Error code: " . $gsOtp->getErrorCode() . PHP_EOL;
    echo "Error message" . $gsOtp->getErrorMessage() . PHP_EOL;
} else {
    echo "ReferenceID: {$referenceID}";
}
```

## Verify OTP
```php
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
```

## License

MIT
