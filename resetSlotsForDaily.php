<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'RestHeaderHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$character_classes = getCharacterClasses($pdo, $input['playerName'], $input['characterName']);
foreach($character_classes AS $character_class) {
	resetSlots($pdo, $input['playerName'], $input['characterName'], $character_class['class_name'], $errors);
}

$log[] = "SUCCESS|";
$log[] = "Action: Daily Reset";
$log[] = "Player Name: " . $input['playerName'];
$log[] = "Character Name : " . $input['characterName'];

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function resetSlots(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
	$sql_exec = "CALL resetSlots(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in resetSlots : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getCharacterClasses(\PDO $pdo, $player_name, $character_name) {
	$sql_exec = "CALL getCharacterClasses(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->execute();

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
