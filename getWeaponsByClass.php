<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __dir__ . '/webio/characterClassId.php';

// Character Class ID
getCharacterClassId($errors, $input);

$weapons = getWeaponsByClass($pdo, $input[CHARACTER_CLASS_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($weapons);
}

function getWeaponsByClass(\PDO $pdo, $character_class_id, &$errors) {
	$sql_exec = "CALL getWeaponsByClass(:characterClassId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':characterClassId', $character_class_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getWeaponsByClass : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
