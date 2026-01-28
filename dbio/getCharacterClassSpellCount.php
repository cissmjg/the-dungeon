<?php

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/DBConnection.php';

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/characterClassName.php';

getCharacterClassName($errors, $input);

$result = getCharacterClassSpellCount($pdo, $input[CHARACTER_CLASS_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_decode($errors);
} else {
	echo json_encode($result);
}

function getCharacterClassSpellCount(\PDO $pdo, $character_class_name, &$errors) {
	$sql_exec = "CALL getCharacterClassSpellCount(:characterClassName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getCharacterClassSpellCount : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}