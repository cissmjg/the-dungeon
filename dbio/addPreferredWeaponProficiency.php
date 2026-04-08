<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../characterActionRoutes.php';

require_once __DIR__ . '/constants/characterClasses.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/characterClassId.php';
require_once __DIR__ . '/../webio/characterLevel.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';

$input = [];
$log = [];
$errors = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getCharacterClassId($errors, $input);
getCharacterLevel($errors, $input);
getWeaponProficiencyId($errors, $input);

$character_level = $input[CHARACTER_LEVEL];

if ($input[CHARACTER_CLASS_ID] == CAVALIER) {
    if ($character_level == 3 || $character_level == 5) {
        addPreferredWeaponForCavalier($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $character_level, $input[WEAPON_PROFICIENCY_ID], $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }
    } else {
        $errors[] = "Invalid level for Cavalier";
        die(json_encode($errors));
    }
}

if ($input[CHARACTER_CLASS_ID] == ELVEN_CAVALIER) {
    if ($character_level == 4 || $character_level == 6) {
        addPreferredWeaponForElvenCavalier($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $character_level, $input[WEAPON_PROFICIENCY_ID], $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }
    } else {
        $errors[] = "Invalid level for Elven Cavalier";
        die(json_encode($errors));
    }
}

RestHeaderHelper::emitRestHeaders();
$log[] = "SUCCESS|";
$log[] = "Preferred Weapon Added|";
$log[] = "Weapon Proficiency ID: " . $input[WEAPON_PROFICIENCY_ID];

echo json_encode($log);

function addPreferredWeaponForCavalier(PDO $pdo, $player_name, $character_name, $character_level, $weapon_proficiency_id, &$errors) {
	$sql_exec = "CALL addPreferredWeaponForCavalier(:playerName, :characterName, :characterLevel, :weaponProficiencyId)";

	$statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':characterLevel', $character_level, PDO::PARAM_INT);
    $statement->bindParam(':weaponProficiencyId', $weapon_proficiency_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addPreferredWeaponForCavalier : " . $e->getMessage();
	}
}

function addPreferredWeaponForElvenCavalier(PDO $pdo, $player_name, $character_name, $character_level, $weapon_proficiency_id, &$errors) {
	$sql_exec = "CALL addPreferredWeaponForElvenCavalier(:playerName, :characterName, :characterLevel, :weaponProficiencyId)";

    $statement = $pdo->prepare($sql_exec);
    $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    $statement->bindParam(':characterLevel', $character_level, PDO::PARAM_INT);
    $statement->bindParam(':weaponProficiencyId', $weapon_proficiency_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addPreferredWeaponForElvenCavalier : " . $e->getMessage();
	}
}

?>