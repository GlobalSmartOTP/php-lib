<?php
require dirname(__FILE__) . '/../vendor/autoload.php';

use GlobalSmartOTP\Api\OTPHandler;
use PHPUnit\Framework\TestCase;

class TestOTPHandler extends TestCase
{

    private static $OTPHandler;
    private static $apiKey;
    private static $mobile;
    //private static $email;
    private static $referenceID;

    public static function setUpBeforeClass(): void
    {
        self::$apiKey = getenv("API_KEY");
        self::$mobile = getenv("MOBILE");
        //self::$email = getenv("EMAIL");
        self::$referenceID = getenv("REFERENCE_ID");
        self::$OTPHandler = new OTPHandler(self::$apiKey);
    }

    public function testSendSMSOtpSuccess()
    {
        $this->assertGreaterThan(0,  self::$OTPHandler->sendSMS(self::$mobile));
    }

    public function testSendSMSOtpFailed()
    {
        $this->assertEquals(0, self::$OTPHandler->sendSMS("132"));
        $this->assertGreaterThan(0, self::$OTPHandler->getErrorCode());
    }

    public function testSendIVROtpSuccess()
    {
        $this->assertGreaterThan(0,  self::$OTPHandler->sendIVR(self::$mobile));
    }

    public function testSendIVROtpFailed()
    {
        $this->assertEquals(0, self::$OTPHandler->sendIVR("456"));
        $this->assertGreaterThan(0, self::$OTPHandler->getErrorCode());
    }

    // TODO: To be completed later
    /*
    public function testSendEmailOtpSuccess()
    {
        $this->assertGreaterThan(0,  self::$OTPHandler->sendEmail(self::$email));
    }

    public function testSendEmailOtpFailed()
    {
        $this->assertEquals(0, self::$OTPHandler->sendEmail("www.yoyo"));
        $this->assertGreaterThan(0, self::$OTPHandler->getErrorCode());
    }

    public function testSendGapOtpSuccess()
    {
        $this->assertGreaterThan(0,  self::$OTPHandler->sendGap(self::$mobile));
    }

    public function testSendGapOtpFailed()
    {
        $this->assertEquals(0, self::$OTPHandler->sendGap("789"));
        $this->assertGreaterThan(0, self::$OTPHandler->getErrorCode());
    }
    */

    public function testCheckStatusOtpSuccess()
    {
        $checkStatus = self::$OTPHandler->status(self::$referenceID);
        $this->assertIsArray($checkStatus);
        $this->assertArrayHasKey('status', $checkStatus);
        $this->assertArrayHasKey('method', $checkStatus);
        $this->assertArrayHasKey('verified', $checkStatus);
        $this->assertContains($checkStatus['status'], ['pending', 'sent', 'deliver', 'failed']);
        $this->assertContains($checkStatus['method'], ['sms', 'ivr', 'email', 'gap']);
    }

    public function testCheckStatusOtpFail()
    {
        $checkStatus = self::$OTPHandler->status("134486546");
        $this->assertIsArray($checkStatus);
        $this->assertEquals([], $checkStatus);
        $this->assertGreaterThan(0, self::$OTPHandler->getErrorCode());
    }

    //TODO: write test for verify otp
    // public function testVerifyOtp(){}
}
