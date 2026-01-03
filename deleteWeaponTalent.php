<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/webio/requiredParameter.php';

// Weapon Proficiency (Talent) ID
getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID);

deleteWeaponTalent($pdo, $input[WEAPON_PROFICIENCY_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($result);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Weapon Proficiency Delete|";
    $log[] = "weaponProficiencyId: " . $input[WEAPON_PROFICIENCY_ID];
    echo json_encode($log);
}

function deleteWeaponTalent(\PDO $pdo, $weapon_talent_id, &$errors) {
	$sql_exec = "CALL deleteWeaponTalentForPlayerCharacter(:weaponProficiencyId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':weaponProficiencyId', $weapon_talent_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteWeaponTalent : " . $e->getMessage();
	}
}