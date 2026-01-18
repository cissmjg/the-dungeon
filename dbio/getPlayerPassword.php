<?php

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/DBConnection.php';
require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerName.php';

getPlayerName($errors, $input);

$result = getPlayerPassword($pdo, $input[PLAYER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);	
} else {
	echo json_encode($result);
}

function getPlayerPassword(\PDO $pdo, $player_name, &$errors) {
	$sql_exec = "CALL getPasswordHash(:playerName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getPlayerPassword : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}