<?php

$errors = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

// validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'accountCharacterSummary.php';

const PORTRAIT_DIR = "portraits/";
const UNKNOWN_PORTRAIT = "Unknown.jpg";

$player_name = filter_input(INPUT_GET, PLAYER_NAME, FILTER_SANITIZE_STRING);
if ($player_name == NULL ) {
	$errors[] = "Input Error|";
	$errors[] = __FILE__ . "|";
	$errors[] = 'Player name is missing';
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$account_character_summaries = [];
$characters_for_player = getCharactersForPlayer($pdo, $player_name, $errors);
foreach($characters_for_player AS $character_for_player) {
	$character_name = $character_for_player['character_name'];
	$account_class_summary = new AccountCharacterSummary();
	$account_class_summary->init($pdo, $player_name, $character_name);
	$normalized_portrait_file_loc = normalizePortraitFileLocation($character_for_player['player_character_portrait']);
	$account_class_summary->setCharacterPortrait($normalized_portrait_file_loc);
	$account_character_summaries[] = $account_class_summary;
}

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
}

echo json_encode($account_character_summaries);

function getCharactersForPlayer(\PDO $pdo, $player_name, &$errors) {
	$sql_exec = "CALL getCharactersForPlayer(:playerName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getCharactersForPlayer : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function normalizePortraitFileLocation($not_verified_portrait_file_location) {
	if (empty($not_verified_portrait_file_location)) {
		return PORTRAIT_DIR . UNKNOWN_PORTRAIT;
	}

	$file_loc = PORTRAIT_DIR . $not_verified_portrait_file_location;
	if (!file_exists($file_loc)) {
		return PORTRAIT_DIR . UNKNOWN_PORTRAIT;
	}

	return $file_loc;
}