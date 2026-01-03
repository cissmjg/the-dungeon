<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';

$input = [];
$log = [];
$errors = [];

// Filter and sanitize IDs
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID);
getRequiredStringParameter($errors, $input, __FILE__, 'isPreferred');
$is_preferred = $input['isPreferred'] == 'preferred' ? true : false;

$log[] = "SUCCESS|";
addWeaponTalent($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input[WEAPON_PROFICIENCY_ID], $is_preferred, $errors);

RestHeaderHelper::emitRestHeaders();
if (count($errors) > 0) {
	echo json_encode($errors);
} else {
	echo json_encode($log);
}

function addWeaponTalent(\PDO $pdo, $player_name, $character_name, $weapon_proficiency_id, $is_preferred, &$errors) {
	$sql_exec = "CALL addWeaponTalentToPlayerCharacter(:playerName, :characterName, :weaponProficiencyId, :isPreferred)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
	$statement->bindParam(':weaponProficiencyId', $weapon_proficiency_id, PDO::PARAM_INT);
	$statement->bindParam(':isPreferred', $is_preferred, PDO::PARAM_BOOL);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addWeaponTalent : " . $e->getMessage();
	}
}
