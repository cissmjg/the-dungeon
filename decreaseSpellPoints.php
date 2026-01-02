<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'characterName.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'spellLevel.php';

// This module decreases available spell points for a spell level being cast

$log = [];
$errors = [];

// Filter and sanitize IDs
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getSpellLevel($errors, $input);

$player_name = $input['playerName'];
$character_name = $input[CHARACTER_NAME];
$spell_level = $input['spellLevel'];

$log[] = "SUCCESS|";

// spell_level is the number of points to be decreased from the Greater Mage total
if ($spell_level == 0) {
	$spell_point_decrease = -0.25;
} else {
	$spell_point_decrease = $spell_level * -1.0;
}

adjustSpellPoints($pdo, $player_name, $character_name, $spell_point_decrease, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function adjustSpellPoints(\PDO $pdo, $player_name, $character_name, $spell_point_decrease, &$errors) {
	$sql_exec = "CALL adjustSpellPoints(:playerName, :characterName, :spellPointsAdjustText)";

    $point_decrease_as_string = strval($spell_point_decrease);
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':spellPointsAdjustText', $point_decrease_as_string, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in adjustSpellPoints : " . $e->getMessage();
	}
}