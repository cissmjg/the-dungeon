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

getPlayerName($errors, $input);
getCharacterName($errors, $input);

deletePlayerCharacter($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($result);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Delete|";
    $log[] = "playerName: " . $input[PLAYER_NAME];
    $log[] = "chracterName: " . $input[CHARACTER_NAME];
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
		$errors[] = "Exception in deletePlayerCharacter : " . $e->getMessage();
	}
}