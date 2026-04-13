<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/twoWeaponConfigurationId.php';

getTwoWeaponConfigurationId($errors, $input);

deleteTwoWeaponConfiguration($pdo, $input[TWO_WEAPON_CONFIGURATION_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Two Weapon Configuration Delete|";
    $log[] = "playerCharacterTwoWeaponConfigId: " . $input[TWO_WEAPON_CONFIGURATION_ID];

    echo json_encode($log);
}

function deleteTwoWeaponConfiguration(\PDO $pdo, $player_character_two_weapon_config_id, &$errors) {
	$sql_exec = "CALL deleteTwoWeaponConfiguration(:playerCharacterTwoWeaponConfigId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterTwoWeaponConfigId', $player_character_two_weapon_config_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteTwoWeaponConfiguration : " . $e->getMessage();
	}
}