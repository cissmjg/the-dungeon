<?php

$errors = [];
$input = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';
require_once 'characterSummary.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

// If errors exist return them
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

RestHeaderHelper::emitRestHeaders();
$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input['characterName']);

echo json_encode($character_summary);
