<?php

$errors = [];
$input = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

require_once __DIR__ . '/../helper/RestHeaderHelper.php';

require_once __DIR__ . '/../webio/playerName.php';

validateSessionCredentials($pdo);

getPlayerName($errors, $input);

$player_name = $input[PLAYER_NAME];

$result = getCharactersForPlayer($pdo, $player_name, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	json_encode($errors);
} else {
	echo json_encode($result);
}

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