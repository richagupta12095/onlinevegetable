<?php
class SmsBump {
	private $APIKey = '';
	private $from = '';
	private $to = '';

	public static function getAPIUrl($method, $APIKey) {
		return "https://api.smsbump.com/{$method}/{$APIKey}.json";
	}

	public static function sendBulk($APIKey, $from, $to, $message, $callback = NULL) {
		foreach ($to as $toNumber) {
			SmsBump::sendSingle($APIKey, $from, $toNumber, $message, $callback);
		}
	}

	public static function sendSingle($APIKey, $from, $to, $message, $callback = NULL) {
		$postData = array(
			'from' => $from,
			'to' => $to,
			'message' => $message,
			'platform' => 'opencart3'
		);
		$postString = http_build_query($postData);

		$ch = curl_init(SmsBump::getAPIUrl('send', $APIKey));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch,CURLOPT_POST, count($postData));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $postString);

		$result = curl_exec($ch);
		curl_close($ch);

		if (is_callable($callback)) {
			call_user_func($callback, json_decode($result, true));
		}
	}

	public static function sendStatic_1($args) {
		if (!empty($args['APIKey']) && !empty($args['to']) && !empty($args['message'])) {
			$APIKey = $args['APIKey'];
			$from = !empty($args['from']) ? $args['from'] : '';
			$to = $args['to'];
			$message = $args['message'];
			$callback = !empty($args['callback']) ? $args['callback'] : NULL;
			SmsBump::sendStatic($APIKey, $from, $to, $message, $callback);
		}
	}

	public static function sendStatic_5($APIKey, $from, $to, $message, $callback = NULL) {
		SmsBump::sendStatic($APIKey, $from, $to, $message, $callback);
	}

	public static function sendStatic($APIKey, $from, $to, $message, $callback = NULL) {
		if (!is_array($to)) {
			SmsBump::sendSingle($APIKey, $from, $to, $message, $callback);
		} else {
			SmsBump::sendBulk($APIKey, $from, $to, $message, $callback);
		}
		return true;
	}

	public static function sendMessage() {
		$args = func_get_args();
		$argsCount = count($args);
		if (in_array($argsCount, array(1,5))) {
			$name = 'sendStatic_'.$argsCount;
			call_user_func_array(array('SmsBump', $name), $args);
		}
	}

	public function __construct($APIKey) {
		$this->APIKey = $APIKey;
	}

	public function setAPIKey($APIKey) {
		$this->APIKey = $APIKey;
	}

	public function setFrom($from) {
		$this->from = $from;
	}

	public function setTo($to) {
		$this->to = $to;
	}

	public function send() {
		$args = func_get_args();
		$argsCount = count($args);
		if (in_array($argsCount, array(1,2,5))) {
			$name = 'send_'.$argsCount;
			call_user_func_array(array($this, $name), $args);
		}
	}

	public function send_1($args) {
		if (!empty($args['APIKey']) && !empty($args['to']) && !empty($args['message'])) {
			$APIKey = $args['APIKey'];
			$from = !empty($args['from']) ? $args['from'] : '';
			$to = $args['to'];
			$message = $args['message'];
			$callback = !empty($args['callback']) ? $args['callback'] : NULL;
			SmsBump::sendStatic($APIKey, $from, $to, $message, $callback);
		}
	}

	public function send_2($message, $callback = NULL) {
		SmsBump::sendStatic($this->APIKey, $this->from, $this->to, $message, $callback);
	}

	public function send_5($APIKey, $from, $to, $message, $callback = NULL) {
		SmsBump::sendStatic($APIKey, $from, $to, $message, $callback);
	}
}
