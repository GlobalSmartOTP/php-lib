![gsOTP](examples/assets/logo.png)

# GlobalSmartOTP PHP SDK

A PHP SDK for the GlobalSmartOTP API.

## Available Methods
- SMS (Iran: 2000, 3000, 9000)
- Messenger
  - [Whatsapp](https://whatsapp.com) Messenger
  - [Gap](https://gap.im) Messenger
- IVR

## Requirements

- PHP 7.4 or higher
- ext-curl
- ext-json
- composer

## Installation

### with Composer
```shell
$ composer require globalsmartotp/php-lib
```
#### Require
```php
require dirname(__FILE__) . '/../vendor/autoload.php';
use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
```

### without Composer
```sh
$ git clone git@github.com:GlobalSmartOTP/php-lib.git
```
#### Require
```php
require dirname(__FILE__) . '/php-lib/src/OTPHandler.php';
use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
```
----

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
$provider = 'whatsapp'; // whatsapp, gap
try {
	$referenceID = OTPHandler::ByMessenger($apiKey, $mobile, $templateID, 'whatsapp');
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
	echo "OTPStatus: " . $gsOTP->getOTPStatus() . PHP_EOL;
	echo "OTPVerified: " . $gsOTP->isOTPVerified() . PHP_EOL;
	echo "OTPMethod: " . $gsOTP->getOTPMethod() . PHP_EOL;
} catch (\Exception $e) {
	echo "Error:" . $e->getMessage();
}
```
## License

MIT
