<?php

$input = [];
$errors = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

// validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../classes/accountCharacterSummary.php';
require_once __DIR__ . '/../webio/playerName.php';

const PORTRAIT_DIR = "../portraits/";
const UNKNOWN_PORTRAIT = "Unknown.jpg";

getPlayerName($errors, $input);
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$player_name = $input[PLAYER_NAME];

$account_character_summaries = [];
$characters_for_player = getCharactersForPlayer($pdo, $player_name, $errors);
foreach($characters_for_player AS $character_for_player) {
	$character_name = $character_for_player['character_name'];
	$account_class_summary = new AccountCharacterSummary();
	$account_class_summary->init($pdo, $player_name, $character_name, $errors);
	$normalized_portrait_file = verifyPortraitFile($character_for_player['player_character_portrait']);
	$account_class_summary->setCharacterPortrait($normalized_portrait_file);
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

function verifyPortraitFile($portrait_file) {
	if (empty($portrait_file)) {
		return UNKNOWN_PORTRAIT;
	}

	$file_loc = PORTRAIT_DIR . $portrait_file;
	if (!file_exists($file_loc)) {
		return UNKNOWN_PORTRAIT;
	}

	return $portrait_file;
}