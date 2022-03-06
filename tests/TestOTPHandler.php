<?php
require dirname(__FILE__) . '/../vendor/autoload.php';

use GlobalSmartOTP\Api\OTPHandler;
use PHPUnit\Framework\TestCase;

class TestOTPHandler extends TestCase
{

	private static OTPHandler $OTPHandler;
	private static string $apiKey;
	private static string $mobile;

	public static function setUpBeforeClass(): void
	{
		self::$apiKey = getenv("API_KEY");
		self::$mobile = getenv("MOBILE");
		self::$OTPHandler = new OTPHandler(self::$apiKey);
	}

	/**
	 * @throws Exception
	 */
	public function testSendBySMS(): string
	{
		$referenceID = self::$OTPHandler->sendSMS(self::$mobile);
		$this->assertIsString($referenceID);
		return $referenceID;
	}

	/**
	 * @throws Exception
	 */
	public function testSendByMessenger()
	{
		$this->assertIsString(self::$OTPHandler->setMobile(self::$mobile)->sendMessenger());
	}

	/**
	 * @throws Exception
	 */
	public function testSendByIvr()
	{
		$this->assertIsString(self::$OTPHandler->sendIvr(self::$mobile));
	}

	/**
	 * @throws Exception
	 */
	public function testSendBySmart()
	{
		$this->assertIsString(self::$OTPHandler->sendSmart(self::$mobile, 3));
	}

	/**
	 * @depends testSendBySMS
	 * @throws Exception
	 */
	public function testCheckStatus(string $referenceID)
	{
		self::$OTPHandler->status($referenceID);
		$this->assertContains(self::$OTPHandler->getOTPStatus(), ['pending', 'sent']);
		$this->assertEquals(false, self::$OTPHandler->isOTPVerified());
		$this->assertContains(self::$OTPHandler->getOTPMethod(), ['sms', 'ivr', 'email', 'messenger']);
	}

	/**
	 * @throws Exception
	 */
	public function testVerify()
	{
		try {
			self::$OTPHandler->setMobile("+989356" . rand(100000, 999999))->setOTP(rand(1000, 9999))->verify();
			$this->fail('verify OTP was not thrown');
		} catch (Exception $e) {
			$this->assertGreaterThan(0, $e->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	public function testSendByMessengerWithParams(): string
	{
		$otpCode = rand(1000, 9999);
		$referenceID = self::$OTPHandler->setMobile(substr(self::$mobile, 3))
			->setCountryCode("98")
			->setTemplateID(11)
			->setParam1("value_1")
			->setParam2("value_2")
			->setParam3("value_3")
			->setParam4("value_4")
			->setParam5("value_5")
			->setCode($otpCode)
			->setLength(strlen($otpCode))
			->sendMessenger();
		$this->assertIsString($referenceID);
		return $otpCode;
	}

}
