![gsOTP](examples/assets/logo.png)

# GlobalSmartOTP PHP SDK

A PHP SDK for the GlobalSmartOTP API.

## Requirements

- PHP 7.4 or higher
- ext-curl
- ext-json

## Installation

```sh
$ git clone git@github.com:GlobalSmartOTP/php-lib.git
$ cd php-lib/
$ composer dumpautoload
```

### Require
```php
require dirname(__FILE__) . '/../vendor/autoload.php';
use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
```

## Send OTP 

### By SMS
```php
try {
	$referenceID = OTPHandler::BySms($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
### By  Messenger
```php
try {
	$referenceID = OTPHandler::ByMessenger($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
### By  IVR
```php
$templateID = 2;
try {
	$referenceID = OTPHandler::ByIvr($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
### By  Smart
```php
try {
	$referenceID = OTPHandler::BySmart($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
---
## Verify
```php
try {
	OTPHandler::isVerify($apiKey, $mobile, $otp);
	echo "OTP is verified";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
---
## Status
```php
try {
	$gsOTP = OTPHandler::checkStatus($apiKey, $referenceID);
	echo "OTPStatus: " . $gsOTP->OTPStatus . PHP_EOL;
	echo "OTPVerified:" . $gsOTP->OTPVerified . PHP_EOL;
	echo "OTPMethod: " . $gsOTP->OTPMethod . PHP_EOL;
} catch (\Exception $e) {
	echo "Error:" . $e->getMessage();
}
```
## License

MIT
