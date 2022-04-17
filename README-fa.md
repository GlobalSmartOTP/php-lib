<div dir=rtl>

	
# پروژه آرشیو شده! لطفا از   [راه پیام](https://github.com/MessageWay/php-lib) استفاده کنید.	
![DEPRECATED][ico-deprecated]

[ico-deprecated]: https://img.shields.io/badge/-DEPRECATED-red?style=for-the-badge

	
</div>

------
	
	
<div dir=rtl>
	
	
![gsOTP](examples/assets/logo.png)


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
![Swagger][ico-swagger]
[![Global Smart OTP][ico-gsOTP]][link-gsOTP]
![🇬🇧](https://github.com/GlobalSmartOTP/php-lib/blob/main/README.md)

# سرویس gsOTP
این کتابخانه برای کار با سامانه GlobalSmartOTP آماده شده است.

برای مشاهده نمونه کدها می‌توانید فایل‌های داخل پوشه [examples](https://github.com/GlobalSmartOTP/php-lib/tree/main/examples) را بررسی کنید.

## روش‌های ارسال
- پیامک (از سرشماره‌های: 2000, 3000, 9000)
- پیام‌رسان‌ها:
  - پیام‌رسان [واتساپ](https://whatsapp.com)
  - پیام‌رسان [گپ](https://gap.im)
- تماس صوتی

## نیازمندی‌ها

- PHP 7.4 یا بالاتر
- ext-curl
- ext-json
- composer

## نصب

### با استفاده از Composer
ابتدا دستور ذیل را از طرق ترمینال اجرا کنید.
<div dir=ltr>

```shell
$ composer require globalsmartotp/php-lib
```

</div>

سپس کدهای ذیل را در ابتدای فایل موردنظر درج کنید، مقدار apiKey را باید پس از ثبت نام از طریق منو پروژه‌ها در سایت gsOTP بدست آورید. 
<div dir=ltr>

```php
require dirname(__FILE__) . '/../vendor/autoload.php';
use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
```

</div>

### بدون استفاده از Composer
ابتدا پروژه را از طریق اجرای دستور ذیل در ترمینال دریافت کنید.

<div dir=ltr>

```sh
$ git clone git@github.com:GlobalSmartOTP/php-lib.git
```
</div>

همچنین دانلود پروژه با کلیک روی [این دکمه](https://github.com/GlobalSmartOTP/php-lib/archive/refs/heads/main.zip)  امکان پذیر است.

سپس کدهای ذیل را در ابتدای فایل موردنظر درج کنید، مقدار apiKey را باید پس از ثبت نام از طریق منو پروژه‌ها در سایت gsOTP بدست آورید.

<div dir=ltr>

```php
require dirname(__FILE__) . '/php-lib/src/OTPHandler.php';
use GlobalSmartOTP\Api\OTPHandler;

// Get apiKey from https://gsotp.com/dashboard/document/
$apiKey = "";
$mobile = "";
$templateID = 3;
```
</div>

----

## ارسال  OTP 

### از طریق پیامک

<div dir=ltr>

```php
try {
	$referenceID = OTPHandler::BySms($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
</div>

### از طریق پیام‌رسان 

<div dir=ltr>

```php
$provider = 'whatsapp'; // whatsapp, gap
try {
	$referenceID = OTPHandler::ByMessenger($apiKey, $mobile, $templateID, 'whatsapp');
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
</div>


### از طریق تماس صوتی

<div dir=ltr>

```php
$templateID = 2;
try {
	$referenceID = OTPHandler::ByIvr($apiKey, $mobile, $templateID);
	echo "ReferenceID: {$referenceID}";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```

</div>


---
## تایید کد OTP 

<div dir=ltr>

```php
try {
	OTPHandler::isVerify($apiKey, $mobile, $otp);
	echo "OTP is verified";
} catch (\Exception $e) {
	echo $e->getMessage();
}
```
</div>

---
## بررسی وضعیت

<div dir=ltr>

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

</div>

</div>

[ico-version]: https://img.shields.io/packagist/v/globalsmartotp/php-lib.svg?style=for-the-badge
[ico-downloads]: https://img.shields.io/packagist/dt/globalsmartotp/php-lib.svg?style=for-the-badge
[ico-gsOTP]: https://img.shields.io/badge/-gsOTP.com-critical?link=https://gsotp.com&style=for-the-badge
[ico-swagger]: https://img.shields.io/swagger/valid/3.0?specUrl=https%3A%2F%2Fdoc.gsotp.com%2Fswagger.json&style=for-the-badge

[link-packagist]: https://packagist.org/packages/globalsmartotp/php-lib
[link-downloads]: https://packagist.org/packages/globalsmartotp/php-lib
[link-gsOTP]: https://gsotp.com/

