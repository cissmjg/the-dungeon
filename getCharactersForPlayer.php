<?php

$errors = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';

$player_name = filter_input(INPUT_GET, PLAYER_NAME, FILTER_SANITIZE_STRING);
if ($player_name == NULL ) {
	$errors['errorPlayerName'] = 'Player name is missing';
	RestHeaderHelper::emitRestHeaders();
	die(json_encode($errors));
} 

$result = getCharactersForPlayer($pdo, $player_name, $errors);

RestHeaderHelper::emitRestHeaders();
echo json_encode($result);

function getCharactersForPlayer(\PDO $pdo, $player_name, &$errors) {
	$sql_exec = "CALL getCharactersForPlayer(:playerName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getCharactersForPlayer : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}