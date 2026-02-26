<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../characterActionRoutes.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/skillCatalogId.php';
require_once __DIR__ . '/../webio/playerCharacterSkillName.php';
require_once __DIR__ . '/../webio/isSkillFocus.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';
require_once __DIR__ . '/../webio/weapon2ProficiencyId.php';

$input = [];
$log = [];
$errors = [];

// Filter and sanitize weapon related fields
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getSkillCatalogId($errors, $input);
getOptionalPlayerCharacterSkillName($errors, $input);
getIsSkillFocus($errors, $input);
getOptionalWeaponProficiencyId($errors, $input);
getOptionalWeapon2ProficiencyId($errors, $input);

if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
	echo json_encode($errors);
    exit;
}

$player_character_weapon_skill_id = addSkillToPlayerCharacter($pdo, $input, $errors);

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Skill Delete|";
    $log[] = "playerCharacterSkillId: " . $input[PLAYER_CHARACTER_SKILL_ID];

    echo json_encode($log);
}

function addSkillToPlayerCharacter(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL addSkill(:playerName, :characterName, :skillCatalogId, :playerSkillName, :isSkillFocus, :weaponProficiencyId, :weapon2ProficiencyId)";

    $null_value = NULL;
    $true_value = true;
    $false_value = false;

	$statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);

    $statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);

    $statement->bindParam(':skillCatalogId', $input[SKILL_CATALOG_ID], PDO::PARAM_INT);

    if ($input[PLAYER_CHARACTER_SKILL_NAME] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerSkillName', $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':playerSkillName', $input[PLAYER_CHARACTER_SKILL_NAME], PDO::PARAM_STR);
    }

    if (strcasecmp($input[IS_SKILL_FOCUS], 'YES') == 0) {
        $statement->bindParam(':isSkillFocus', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isSkillFocus', $false_value, PDO::PARAM_BOOL);
    }

    if ($input[WEAPON_PROFICIENCY_ID] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':weaponProficiencyId', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':weaponProficiencyId', $input[WEAPON_PROFICIENCY_ID], PDO::PARAM_INT);
    }

    if ($input[WEAPON2_PROFICIENCY_ID] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':weapon2ProficiencyId', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':weapon2ProficiencyId', $input[WEAPON2_PROFICIENCY_ID], PDO::PARAM_INT);
    }

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addSkillToPlayerCharacter : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}
