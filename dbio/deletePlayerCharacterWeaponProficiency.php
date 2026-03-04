<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerCharacterWeaponSkillId.php';

getPlayerCharacterWeaponSkillId($errors, $input);

deleteWeaponProficiencyForPlayerCharacter($pdo, $input[PLAYER_CHARACTER_WEAPON_SKILL_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Weapon Proficiency Delete|";
    $log[] = "playerCharacterWeaponSkillId: " . $input[PLAYER_CHARACTER_WEAPON_SKILL_ID];

    echo json_encode($log);
}

function deleteWeaponProficiencyForPlayerCharacter(\PDO $pdo, $player_character_weapon_proficiency_id, &$errors) {
	$sql_exec = "CALL deleteWeaponProficiencyForPlayerCharacter(:playerCharacterWeaponProficiencyId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterWeaponProficiencyId', $player_character_weapon_proficiency_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteWeaponProficiencyForPlayerCharacter : " . $e->getMessage();
	}
}