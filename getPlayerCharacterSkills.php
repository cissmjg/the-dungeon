<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once 'characterName.php';
require_once 'characterSkills.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$character_skills = new CharacterSkills();
$character_skills->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors, $log);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($character_skills->getPlayerCharacterSkills());
}
