<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerName.php';
require_once 'characterName.php';

getPlayerName($errors, $input);
getCharacterName($errors, $input);

deletePlayerCharacter($pdo, $input['playerName'], $input['characterName'], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($result);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Delete|";
    $log[] = "playerName: " . $input['playerName'];
    $log[] = "chracterName: " . $input['characterName'];
    echo json_encode($log);
}

function deletePlayerCharacter(\PDO $pdo, $player_name, $character_name, &$errors) {
	$sql_exec = "CALL deletePlayerCharacter(:playerName, :characterName)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getCharactersForPlayer : " . $e->getMessage();
	}
}