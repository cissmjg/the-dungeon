<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/constants/skills.php';
require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WeaponIOHelper.php';

require_once __DIR__ . '/../classes/playerCharacterSkill.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/playerCharacterSkillId.php';

getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterSkillId($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];
$player_character_skill_id = $input[PLAYER_CHARACTER_SKILL_ID];

$skill = getSkill($pdo, $player_character_skill_id, $errors);
if (count($errors) > 0) {
	die(json_encode($errors));
}

$player_character_skill = new PlayerCharacterSkill();
$player_character_skill->init($skill);

$skill_catalog_id = $player_character_skill->getSkillCatalogId();

// If Two Weapon Fighting is being deleted, remove all Two Weapon Configurations
if ($skill_catalog_id == TWO_WEAPON_FIGHTING) {
	deleteTwoWeaponConfigurationsForPlayerCharacter($pdo, $player_name, $character_name, $errors);
	if (count($errors) > 0) {
		die(json_encode($errors));
	}
}

// If the skill has a Weapon associated with it, delete the weapon.
// This means the skill is one of the 'Martial' skills (Circle Kick, Mantis Leap, Throw Anything).
if (!empty($player_character_skill->getPlayerCharacterWeaponId())) {
	WeaponIOHelper::deleteWeaponForPlayerCharacter($pdo, $player_character_skill->getPlayerCharacterWeaponId(), $errors);
	if (count($errors) > 0) {
		die(json_encode($errors));
	}
}

deleteSkillForPlayerCharacter($pdo, $player_character_skill_id, $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Skill Delete|";
    $log[] = "playerCharacterWeaponSkillId: " . $player_character_skill_id;

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

function getSkill(PDO $pdo, $player_character_skill_id, &$errors) {
	$sql_exec = "CALL getSkill(:playerCharacterSkillId)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerCharacterSkillId', $player_character_skill_id, PDO::PARAM_INT);
    
	try {
		$statement->execute();
		return $statement->fetch(PDO::FETCH_ASSOC);
	} catch(Exception $e) {
		$errors[] = "Exception in getSkill : " . $e->getMessage();
	}
	
	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function deleteTwoWeaponConfigurationsForPlayerCharacter(PDO $pdo, $player_name, $character_name, &$errors) {
	$sql_exec = "CALL deleteTwoWeaponConfigurationForPlayerCharacter(:playerName, :characterName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
    
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteTwoWeaponConfigurationForPlayerCharacter : " . $e->getMessage();
	}
}