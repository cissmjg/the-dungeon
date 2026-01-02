<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'playerCharacterWeaponId.php';

getPlayerCharacterWeaponId($errors, $input);

deleteWeaponForPlayerCharacter($pdo, $input['playerCharacterWeaponId'], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Weapon Delete|";
    $log[] = "playerCharacterWeaponId: " . $input['playerCharacterWeaponId'];

    echo json_encode($log);
}

function deleteWeaponForPlayerCharacter(\PDO $pdo, $player_character_weapon_id, &$errors) {
	$sql_exec = "CALL deleteWeaponForPlayerCharacter(:characterWeaponId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':characterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteWeaponForPlayerCharacter : " . $e->getMessage();
	}
}