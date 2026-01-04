<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/characterLevel.php';
require_once 'hoursOfSleep.php';

// This module decreases available spell points for a spell level being cast

$log = [];
$errors = [];

// Filter and sanitize IDs
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterLevel($errors, $input);
getHoursOfSleep($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];
$character_level = $input[CHARACTER_LEVEL];
$hours_of_sleep = $input[HOURS_OF_SLEEP];

$log[] = "SUCCESS|";

// The Number of spell points recovers is equal to the caster's level * the number of hours of sleep.
$spell_points = $character_level * $hours_of_sleep;

adjustSpellPoints($pdo, $player_name, $character_name, $spell_points, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function adjustSpellPoints(\PDO $pdo, $player_name, $character_name, $spell_points, &$errors) {
	$sql_exec = "CALL adjustSpellPoints(:playerName, :characterName, :spellPointsAdjustText)";

    $point_increase_as_string = strval($spell_points);
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':spellPointsAdjustText', $point_increase_as_string, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in adjustSpellPoints : " . $e->getMessage();
	}
}