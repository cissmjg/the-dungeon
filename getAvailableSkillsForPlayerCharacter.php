<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'characterName.php';
require_once 'availableCharacterSkills.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$existing_character_skills = getExistingCharacterSkills($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

if(count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$available_skills = new AvailableCharacterSkills();
$available_skills->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $existing_character_skills, $errors, $log);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($available_skills);
	error_log($log);
}

function getExistingCharacterSkills(\PDO $pdo, $player_name, $character_name) {
	// 
	$sql_exec = "CALL getSkillListForPlayerCharacter(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);

	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getSkillListForPlayerCharacter : " . $e->getMessage();
	}    

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
