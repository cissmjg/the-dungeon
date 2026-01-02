<?php

require_once __DIR__ . '/env.php';

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
	
	public static function buildCharacterActionRouterUrl($player_name, $action) {
		$redirect_url = CurlHelper::buildUrl('characterActionRouter');
		$redirect_url = CurlHelper::addParameter($redirect_url, 'characterAction', $action);
		$redirect_url = CurlHelper::addParameter($redirect_url, 'playerName', $player_name);
		return $redirect_url;
	}
	
	public static function buildCharacterCRUDUrl($player_name, $character_name, $navigation_action) {
		$redirect_url = CurlHelper::buildUrl('characterActionRouter');
		$redirect_url = CurlHelper::addParameter($redirect_url, 'characterAction', $navigation_action);
		$redirect_url = CurlHelper::addParameter($redirect_url, 'playerName', $player_name);
		$redirect_url = CurlHelper::addParameter($redirect_url, 'characterName', $character_name);

		return $redirect_url;
	}
	
	public static function buildCharacterCRUDRedirect($player_name, $character_name, $navigation_action) {
		$redirect_url = CurlHelper::buildUrl('characterActionRouter');
		$redirect_url = CurlHelper::addParameter($redirect_url, 'characterAction', $navigation_action);
		$redirect_url = CurlHelper::addParameter($redirect_url, 'playerName', $player_name);
		$redirect_url = CurlHelper::addParameter($redirect_url, 'characterName', $character_name);

		return 'Location:' . CurlHelper::buildCharacterCRUDUrl($player_name, $character_name, $navigation_action);
	}
	
	public static function buildCharacterActionRouterLocationHeader($player_name, $action) {
		$redirect_url = CurlHelper::buildCharacterActionRouterUrl($player_name, $action);
		return 'Location:' . $redirect_url;
	}
}