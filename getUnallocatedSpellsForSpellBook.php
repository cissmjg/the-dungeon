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
require_once 'characterClassName.php';
require_once 'spellLevel.php';

// Filter and sanitize names
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassName($errors, $input);
getSpellLevel($errors, $input);

if (count($errors) > 0) {
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
}

$result = getUnallocatedSpellsForSpellBook($pdo, $input, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($result);
}

function getUnallocatedSpellsForSpellBook(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL getUnallocatedSpellsForSpellBook(:playerName, :characterName, :characterClassName, :spellLevel)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
	$statement->bindParam(':characterClassName', $input['characterClassName'], PDO::PARAM_STR);
	$statement->bindParam(':spellLevel', $input['spellLevel'], PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getUnallocatedSpellsForSpellBook : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>