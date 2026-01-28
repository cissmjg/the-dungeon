<?php

$errors = [];
$input = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/../dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../classes/characterDetails.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$character_details = new CharacterDetails();
$character_details->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	die(json_encode($errors));
} else {
	echo json_encode($character_details);
}
