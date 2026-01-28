<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/characterClassName.php';
require_once __DIR__ . '/constants/emptySpellSlot.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$result = getSpellBookForPlayerCharacter($pdo, $input, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($result);
}

function getSpellBookForPlayerCharacter(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL getSpellBookForPlayerCharacter(:playerName, :characterName, :characterClassName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $input[CHARACTER_CLASS_NAME], PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in promoteCharacterClass : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
