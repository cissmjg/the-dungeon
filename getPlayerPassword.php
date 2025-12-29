<?php

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once 'RestHeaderHelper.php';
require_once 'playerName.php';

getPlayerName($errors, $input);

$result = getPlayerPassword($pdo, $input['playerName'], $errors);

RestHeaderHelper::emitRestHeaders();
echo json_encode($result);

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