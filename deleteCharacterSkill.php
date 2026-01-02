<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'requiredParameter.php';

// Character Skill ID
getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterSkillId');

deleteCharacterSkill($pdo, $input['playerCharacterSkillId'], $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($result);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Skill Delete|";
    $log[] = "playerCharacterSkillId: " . $input['playerCharacterSkillId'];
    echo json_encode($log);
}

function deleteCharacterSkill(\PDO $pdo, $player_character_skill_id, &$errors) {
	$sql_exec = "CALL deleteSkillForPlayerCharacter(:playerCharacterSkillId)";
	
	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterSkillId', $player_character_skill_id, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteCharacterSkill : " . $e->getMessage();
	}
}