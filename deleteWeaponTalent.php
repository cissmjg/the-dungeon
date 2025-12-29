<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once 'RestHeaderHelper.php';
require_once 'requiredParameter.php';

// Weapon Proficiency (Talent) ID
getRequiredIntegerParameter($errors, $input, __FILE__, 'weaponProficiencyId');

deleteWeaponTalent($pdo, $input['weaponProficiencyId'], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($result);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Weapon Proficiency Delete|";
    $log[] = "weaponProficiencyId: " . $input['weaponProficiencyId'];
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