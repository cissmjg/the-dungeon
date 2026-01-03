<?php
$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);

// Exit on error
if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];

$result = getSpellsForExtraSlots($pdo, $player_name, $character_name, $errors);
RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($result);
}

function getSpellsForExtraSlots(\PDO $pdo, $player_name, $character_name, &$errors) {
	$sql_exec = "CALL getSpellsForExtraSlots(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getSpellsForExtraSlots : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>