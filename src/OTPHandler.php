<?php

namespace GlobalSmartOTP\Api;

class OTPHandler
{
	const VERSION = '1.0.0';
	const BASEURL = 'https://api.gsotp.com';

	protected $apiKey;
	protected $acceptLanguage = "fa";

	protected $errorCode;
	protected $errorMessage;

	public function __construct(string $apiKey)
	{
		$this->apiKey = $apiKey;
	}

	/**
	 * Send SMS otp
	 * @param string $mobile
	 * @param string $method
	 * @param int $templateID
	 * @param array $options
	 * @return int
	 */
	public function sendSMS(string $mobile, int $templateID = 3, array $options = [])
	{
		return $this->send($mobile, "sms", $templateID, $options);
	}

	/**
	 * Send IVR otp
	 * @param string $mobile
	 * @param string $method
	 * @param int $templateID
	 * @param array $options
	 * @return int
	 */
	public function sendIVR(string $mobile, int $templateID = 3, array $options = [])
	{
		return $this->send($mobile, "ivr", $templateID, $options);
	}

	/**
	 * Send Email otp
	 * @param string $mobile
	 * @param string $method
	 * @param int $templateID
	 * @param array $options
	 * @return int
	 */
	public function sendEmail(string $mobile, int $templateID = 3, array $options = [])
	{
		return $this->send($mobile, "email", $templateID, $options);
	}

	/**
	 * Send Gap otp
	 * @param string $mobile
	 * @param string $method
	 * @param int $templateID
	 * @param array $options
	 * @return int
	 */
	public function sendGap(string $mobile, int $templateID = 3, array $options = [])
	{
		return $this->send($mobile, "gap", $templateID, $options);
	}

	protected function send(string $mobile, string $method = "sms", int $templateID = 3, array $options = []): int
	{
		$params = $options + [
			'mobile' => $mobile,
			'method' => $method,
			'templateID' => $templateID,
		];

		if (!empty($params['countryCode']) && $params['countryCode'] < 0) {
			$this->setError("countryCode is invalid");
			return 0;
		}
		if (!empty($params['length']) && ($params['length'] < 4 || $params['length'] > 10)) {
			$this->setError("length is invalid. between 4-10");
			return 0;
		}
		if (!empty($params['expireTime']) && ($params['expireTime'] < 60 || $params['expireTime'] > 86400)) {
			$this->setError("expireTime is invalid. between 60-86400");
			return 0;
		}

		try {
			$response = $this->sendRequest("/otp/send", $params);
		} catch (\Exception $e) {
			$this->setError($e->getMessage(), $e->getCode());
			return 0;
		}
		return $response['referenceID'];
	}

	/**
	 * Status OTP
	 * @param int $referenceID
	 * @return int
	 */
	public function status(int $referenceID): array
	{
		$params = [
			'OTPReferenceID' => $referenceID,
		];
		try {
			$response = $this->sendRequest("/otp/status", $params);
		} catch (\Exception $e) {
			$this->setError($e->getMessage(), $e->getCode());
			return [];
		}
		$result = [
			'status' => (string) $response['OTPStatus'],
			'method' => (string) $response['OTPMethod'],
			'verified' => (bool) $response['OTPVerified'],
		];
		return $result;
	}

	/**
	 * Verify OTP
	 * @param string $mobile
	 * @param string $otp
	 * @param array $options
	 * @return bool
	 */
	public function verify(string $mobile, string $otp): bool
	{
		$params = [
			'mobile' => $mobile,
			'otp' => $otp,
		];
		try {
			$response = $this->sendRequest("/otp/verify", $params);
		} catch (\Exception $e) {
			$this->setError($e->getMessage(), $e->getCode());
			return false;
		}
		$result = $response['status'] == 'success' ? true : false;
		return $result;
	}

	/**
	 * Set accept language
	 * @param string $language
	 * @return void
	 */
	public function setAcceptLanguage(string $language)
	{
		$this->acceptLanguage = $language;
	}

	/**
	 * Get error message
	 * @return string
	 */
	public function getErrorMessage(): string
	{
		return $this->errorMessage ?? "";
	}

	/**
	 * Get error code
	 * @return int
	 */
	public function getErrorCode(): int
	{
		return $this->errorCode ?? 0;
	}

	protected function setError(string $message = '', int $code = 0)
	{
		$this->errorCode = $code;
		$this->errorMessage = $message;
	}

	protected function sendRequest(string $endpoint, array $params): array
	{
		if (is_array($params)) {
			$params = json_encode($params);
		}
		$this->setError();
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => self::BASEURL . $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"accept-language: {$this->acceptLanguage}",
				"apiKey: {$this->apiKey}",
			],
		]);
		$response = curl_exec($curl);
		if ($response === false) {
			throw new \Exception('Curl error: ' . curl_error($curl));
		}
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$result = json_decode($response, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new \Exception('incorrect response: ' . $response);
		}
		if ($httpCode != 200 || $result['status'] == 'error') {
			$errMsg = $result['error']['message'] ?? "an error was encountered";
			$errCode = $result['error']['code'] ?? 0;
			throw new \Exception($errMsg, $errCode);
		}
		return $result;
	}
}
