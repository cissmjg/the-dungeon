<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../webio/characterAction.php';

const CHARACTER_ACTION_ROUTER = 'characterActionRouter';

class CurlHelper {

	public static function performGetRequest($url, $data = false) {
		$curl = curl_init();

		if ($data) {
			$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}
	
	public static function buildUrl($endpointPhp) {
		if (!str_ends_with($endpointPhp, ".php")) {
			$endpointPhp .= '.php';
		}
		
		return STARTING_URL . $endpointPhp;
	}
	
	public static function addParameter($url, $paramName, $paramValue) {
		if (str_contains($url, "?")) {
			$url .= "&";
		} else {
			$url .= "?";
		}
		
		$encodedParamName = urlencode($paramName);
		$encodedParamValue = urlencode($paramValue);
		
		return $url . $encodedParamName . "=" . $encodedParamValue;
	}
	
	public static function buildCharacterActionRouterUrl() {
		return CurlHelper::buildUrl(CHARACTER_ACTION_ROUTER);
	}
		
	public static function buildUrlDbioDirectory($dbio_endpoint) {
		$endpoint = 'dbio/' . $dbio_endpoint;
		return  CurlHelper::buildUrl($endpoint);
	}

	public static function buildLocationHeader($endpoint) {
		return 'Location:' . $endpoint;
	}
}