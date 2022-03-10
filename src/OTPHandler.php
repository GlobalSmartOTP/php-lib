<?php

namespace GlobalSmartOTP\Api;

use Exception;

class OTPHandler
{

	const VERSION = '2.0.0';
	const BASEURL = 'https://api.gsotp.com';
	const ACCEPT_LANGUAGE = 'fa';
	const ENDPOINT_SEND = "/otp/send";
	const ENDPOINT_STATUS = "/otp/status";
	const ENDPOINT_VERIFY = "/otp/verify";

	/**
	 * @var string
	 */
	private string $apiKey;
	/**
	 * @var string
	 */
	private string $endpoint;
	/**
	 * @var int
	 */
	private int $httpCode;
	/**
	 * @var string
	 */
	private string $acceptLanguage = self::ACCEPT_LANGUAGE;
	/**
	 * @var string
	 */
	public string $params;
	/**
	 * @var object
	 */
	public object $response;
	/**
	 * @var Exception
	 */
	public Exception $error;
	/**
	 * @var OTPHandler
	 */
	protected static OTPHandler $instance;

	/**
	 * @var int
	 */
	public int $templateID;
	/**
	 * @var string
	 */
	public string $method;
	/**
	 * @var string
	 */
	public string $mobile;
	/**
	 * @var int
	 */
	public int $countryCode;
	/**
	 * @var int
	 */
	public int $length;
	/**
	 * @var string
	 */
	public string $code;
	/**
	 * @var string
	 */
	public string $param1;
	/**
	 * @var string
	 */
	public string $param2;
	/**
	 * @var string
	 */
	public string $param3;
	/**
	 * @var string
	 */
	public string $param4;
	/**
	 * @var string
	 */
	public string $param5;
	/**
	 * @var int
	 */
	public int $expireTime;

	/**
	 * @var string
	 */
	public string $OTPReferenceID;
	/**
	 * @var string
	 */
	private string $OTPStatus;
	/**
	 * @var bool
	 */
	private bool $OTPVerified;
	/**
	 * @var string
	 */
	private string $OTPMethod;
	/**
	 * @var string
	 */
	public string $OTP;

	/**
	 * @param string $apiKey
	 */
	public function __construct(string $apiKey)
	{
		$this->setApiKey($apiKey);
	}

	/**
	 * @param string $apiKey
	 * @return OTPHandler
	 */
	public static function getInstance(string $apiKey): OTPHandler
	{
		if (!isset(static::$instance)) {
			static::$instance = new static($apiKey);
		}
		return static::$instance;
	}

	/**
	 * @throws Exception
	 */
	public static function __callStatic($method, $arguments)
	{
		$apiKey = $arguments[0] ?? '';
		$instance = static::getInstance($apiKey);
		$callMethod = strtolower($method);
		switch (true) {
			case substr($callMethod, 0, 2) == 'by':
			{
				$callMethod = "send" . ucfirst(substr($callMethod, 2));
				$instance->setMobile($arguments[1] ?? '')
					->setTemplateID($arguments[2] ?? 3);
				break;
			}
			case $callMethod == strtolower("checkStatus"):
			{
				$instance->setOTPReferenceID($arguments[1] ?? '');
				$callMethod = "status";
				break;
			}
			case $callMethod == strtolower("isVerify"):
			{
				$instance->setMobile($arguments[1] ?? '')
					->setOTP($arguments[2]);
				$callMethod = "verify";
			}
		}

		if (method_exists(get_class(), $callMethod)) {
			return $instance->{$callMethod}();
		}
		$instance->setError("method `$method` not found!", 500);
		throw $instance->error;
	}

	/**
	 * @param string $mobile
	 * @param int $templateID
	 * @return string
	 * @throws Exception
	 */
	public function sendSms(string $mobile = '', int $templateID = 0): string
	{
		if (!empty($mobile)) {
			$this->setMobile($mobile);
		}
		if (!empty($templateID)) {
			$this->setTemplateID($templateID);
		}
		return $this->setMethod('sms')->send();
	}

	/**
	 * @param string $mobile
	 * @param int $templateID
	 * @return string
	 * @throws Exception
	 */
	public function sendMessenger(string $mobile = '', int $templateID = 0): string
	{
		if (!empty($mobile)) {
			$this->setMobile($mobile);
		}
		if (!empty($templateID)) {
			$this->setTemplateID($templateID);
		}
		return $this->setMethod('messenger')->send();
	}

	/**
	 * @param string $mobile
	 * @param int $templateID
	 * @return string
	 * @throws Exception
	 */
	public function sendIvr(string $mobile = '', int $templateID = 2): string
	{
		if (!empty($mobile)) {
			$this->setMobile($mobile);
		}
		if (!empty($templateID)) {
			$this->setTemplateID($templateID);
		}
		return $this->setMethod('ivr')->send();
	}

	/**
	 * @param string $mobile
	 * @param int $templateID
	 * @return string
	 * @throws Exception
	 */
	public function sendSmart(string $mobile = '', int $templateID = 0): string
	{
		if (!empty($mobile)) {
			$this->setMobile($mobile);
		}
		if (!empty($templateID)) {
			$this->setTemplateID($templateID);
		}
		return $this->setMethod('smart')->send();
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	protected function send(): string
	{
		if (empty($this->mobile)) {
			$this->setError("mobile is empty", 400);
		}
		if (empty($this->apiKey)) {
			$this->setError("apiKey is empty", 400);
		}
		try {
			$this->setEndpoint(self::ENDPOINT_SEND)->setParams()->sendRequest();
		} catch (Exception $e) {
			throw $this->error;
		}
		return $this->response->referenceID ?? '';
	}

	/**
	 * @throws Exception
	 */
	public function status(string $referenceID = ''): OTPHandler
	{
		if (!empty($referenceID)) {
			$this->setOTPReferenceID($referenceID);
		}
		try {
			$this->setEndpoint(self::ENDPOINT_STATUS)
				->setParams()
				->sendRequest();
		} catch (Exception $e) {
			throw $this->error;
		}
		$this->OTPStatus = $this->response->OTPStatus;
		$this->OTPVerified = $this->response->OTPVerified;
		$this->OTPMethod = $this->response->OTPMethod;
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function verify(string $mobile = '', string $OTP = ''): string
	{
		if (!empty($mobile)) {
			$this->setMobile($mobile);
		}
		if (!empty($OTP)) {
			$this->setOTP($OTP);
		}
		try {
			$this->setEndpoint(self::ENDPOINT_VERIFY)
				->setParams()
				->sendRequest();
		} catch (Exception $e) {
			throw $this->error;
		}
		if ($this->response->status != 'success') {
			$this->setError($this->response->error->message, $this->response->error->code);
			throw $this->error;
		}
		return ($this->OTPVerified = ($this->response->status === 'success'));
	}

	/**
	 * @return string
	 */
	public function getOTPStatus(): string
	{
		return $this->OTPStatus;
	}

	/**
	 * @return bool
	 */
	public function isOTPVerified(): bool
	{
		return $this->OTPVerified;
	}

	/**
	 * @return string
	 */
	public function getOTPMethod(): string
	{
		return $this->OTPMethod;
	}

	/**
	 * @param string $OTPReferenceID
	 * @return OTPHandler
	 */
	public function setOTPReferenceID(string $OTPReferenceID): OTPHandler
	{
		$this->OTPReferenceID = $OTPReferenceID;
		return $this;
	}

	/**
	 * @param string $OTP
	 * @return OTPHandler
	 */
	public function setOTP(string $OTP): OTPHandler
	{
		$this->OTP = $OTP;
		return $this;
	}

	/**
	 * @param int $templateID
	 * @return OTPHandler
	 */
	public function setTemplateID(int $templateID): OTPHandler
	{
		$this->templateID = $templateID;
		return $this;
	}

	/**
	 * @param string $method
	 * @return OTPHandler
	 */
	public function setMethod(string $method): OTPHandler
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @param string $mobile
	 * @return OTPHandler
	 */
	public function setMobile(string $mobile): OTPHandler
	{
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * @param int $countryCode
	 * @return OTPHandler
	 */
	public function setCountryCode(int $countryCode): OTPHandler
	{
		$this->countryCode = $countryCode;
		return $this;
	}

	/**
	 * @param int $length
	 * @return OTPHandler
	 */
	public function setLength(int $length): OTPHandler
	{
		$this->length = $length;
		return $this;
	}

	/**
	 * @param string $code
	 * @return OTPHandler
	 */
	public function setCode(string $code): OTPHandler
	{
		$this->code = $code;
		return $this;
	}

	/**
	 * @param string $param1
	 * @return OTPHandler
	 */
	public function setParam1(string $param1): OTPHandler
	{
		$this->param1 = $param1;
		return $this;
	}

	/**
	 * @param string $param2
	 * @return OTPHandler
	 */
	public function setParam2(string $param2): OTPHandler
	{
		$this->param2 = $param2;
		return $this;
	}

	/**
	 * @param string $param3
	 * @return OTPHandler
	 */
	public function setParam3(string $param3): OTPHandler
	{
		$this->param3 = $param3;
		return $this;
	}

	/**
	 * @param string $param4
	 * @return OTPHandler
	 */
	public function setParam4(string $param4): OTPHandler
	{
		$this->param4 = $param4;
		return $this;
	}

	/**
	 * @param string $param5
	 * @return OTPHandler
	 */
	public function setParam5(string $param5): OTPHandler
	{
		$this->param5 = $param5;
		return $this;
	}

	/**
	 * @param int $expireTime
	 * @return OTPHandler
	 */
	public function setExpireTime(int $expireTime): OTPHandler
	{
		$this->expireTime = $expireTime;
		return $this;
	}

	/**
	 * @param string $apiKey
	 * @return OTPHandler
	 */
	public function setApiKey(string $apiKey): OTPHandler
	{
		$this->apiKey = $apiKey;
		return $this;
	}

	/**
	 * @param string $endpoint
	 * @return OTPHandler
	 */
	public function setEndpoint(string $endpoint): OTPHandler
	{
		$this->endpoint = $endpoint;
		return $this;
	}

	/**
	 * @return OTPHandler
	 * @throws Exception
	 */
	public function setParams(): OTPHandler
	{
		$requiredFields = [];
		$optionalFields = [];

		switch ($this->endpoint) {
			case self::ENDPOINT_SEND:
				$requiredFields = [
					'mobile' => $this->mobile,
					'method' => $this->method,
					'templateID' => $this->templateID ?? 3,
				];
				$optionalFields = [
					'countryCode' => $this->countryCode ?? '',
					'length' => $this->length ?? '',
					'code' => $this->code ?? '',
					'param1' => $this->param1 ?? '',
					'param2' => $this->param2 ?? '',
					'param3' => $this->param3 ?? '',
					'param4' => $this->param4 ?? '',
					'param5' => $this->param5 ?? '',
					'expireTime' => $this->expireTime ?? '',
				];
				break;
			case self::ENDPOINT_STATUS:
				$requiredFields = [
					'OTPReferenceID' => $this->OTPReferenceID,
				];
				break;
			case self::ENDPOINT_VERIFY:
				$requiredFields = [
					'mobile' => $this->mobile,
					'OTP' => $this->OTP,
				];
				$optionalFields = [
					'countryCode' => $this->countryCode ?? '',
				];
				break;
			default:
				break;
		}

		foreach ($optionalFields as $field => $value) {
			if (!empty($value)) {
				$requiredFields[$field] = $value;
			}
		}
		$this->params = json_encode($requiredFields);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('error on json_encode params');
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getParams(): string
	{
		return $this->params;
	}

	/**
	 * @param string $acceptLanguage
	 * @return OTPHandler
	 */
	public function setAcceptLanguage(string $acceptLanguage): OTPHandler
	{
		if (in_array($acceptLanguage, ["fa", "en"])) {
			$acceptLanguage = self::ACCEPT_LANGUAGE;
		}
		$this->acceptLanguage = $acceptLanguage;
		return $this;
	}

	/**
	 * @param string $response
	 * @return OTPHandler
	 * @throws Exception
	 */
	public function setResponse(string $response): OTPHandler
	{
		$this->response = json_decode($response);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('incorrect response: ' . $response);
		}
		return $this;
	}

	/**
	 * @param $message
	 * @param $code
	 * @return $this
	 */
	public function setError($message, $code): OTPHandler
	{
		$this->error = new Exception($message, $code);
		return $this;
	}

	/**
	 * @param $curl
	 * @return $this
	 */
	private function setHttpCode($curl): OTPHandler
	{
		$this->httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return $this;
	}

	/**
	 * @throws Exception
	 */
	protected function sendRequest(): OTPHandler
	{
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => self::BASEURL . $this->endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $this->params,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"accept-language: $this->acceptLanguage",
				"apiKey: $this->apiKey",
			],
		]);
		$response = curl_exec($curl);
		if ($response === false) {
			$this->setError('curl error: ' . curl_error($curl), 1);
			throw $this->error;
		}
		$this->setHttpCode($curl)->setResponse($response);
		curl_close($curl);
		if ($this->httpCode != 200 || $this->response->status == 'error') {
			$message = $this->response->error->message ?? "an error was encountered";
			$code = $this->response->error->code ?? 0;
			$this->setError($message, $code);
			throw $this->error;
		}
		return $this;
	}
}