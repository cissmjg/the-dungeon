<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../webio/playerCharacterSkillId.php';

getPlayerCharacterSkillId($errors, $input);

deleteSkillForPlayerCharacter($pdo, $input[PLAYER_CHARACTER_SKILL_ID], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Skill Delete|";
    $log[] = "playerCharacterWeaponSkillId: " . $input[PLAYER_CHARACTER_SKILL_ID];

    echo json_encode($log);
}

function deleteSkillForPlayerCharacter(\PDO $pdo, $player_character_skill_id, &$errors) {
	$sql_exec = "CALL deleteSkillForPlayerCharacter(:playerCharacterSkillId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterSkillId', $player_character_skill_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteSkillForPlayerCharacter : " . $e->getMessage();
	}
}