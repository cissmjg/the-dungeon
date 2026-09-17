<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../helper/WeaponIOHelper.php';
require_once __DIR__ . '/../helper/WeaponSkillHelper.php';
require_once __DIR__ . '/../characterActionRoutes.php';
require_once __DIR__ . '/../dbio/constants/skills.php';
require_once __DIR__ . '/../dbio/constants/weaponSpecializationType.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/skillCatalogId.php';
require_once __DIR__ . '/../webio/playerCharacterSkillName.php';
require_once __DIR__ . '/../webio/isSkillFocus.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';
require_once __DIR__ . '/../webio/weapon2ProficiencyId.php';
require_once __DIR__ . '/../webio/weaponSpecializationTypeId.php';
require_once __DIR__ . '/../webio/playerCharacterWeaponId.php';

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
getOptionalWeaponSpecializationTypeId($errors, $input);

if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
	echo json_encode($errors);
    exit;
}

$skill_catalog_id = $input[SKILL_CATALOG_ID];
$player_character_weapon_id = OPTIONAL_INTEGER_PARAMETER;
error_log("Before Weapon ID: $player_character_weapon_id");

if ($skill_catalog_id == CIRCLE_KICK) {
    $player_character_weapon = WeaponSkillHelper::buildCircleKickWeapon($input[PLAYER_NAME], $input[CHARACTER_NAME]);
    $player_character_weapon_id = WeaponIOHelper::addWeaponToPlayerCharacter($pdo, $player_character_weapon, $errors);
    if (count($errors) > 0) {
        die(json_encode($errors));
    }
} else if ($skill_catalog_id == MANTIS_LEAP) {
    $player_character_weapon = WeaponSkillHelper::buildMantisLeapWeapon($input[PLAYER_NAME], $input[CHARACTER_NAME]);
    $player_character_weapon_id = WeaponIOHelper::addWeaponToPlayerCharacter($pdo, $player_character_weapon, $errors);
    if (count($errors) > 0) {
        die(json_encode($errors));
    }
} else if ($skill_catalog_id == THROW_ANYTHING) {
    $player_character_weapon = WeaponSkillHelper::buildThrowAnythingWeapon($input[PLAYER_NAME], $input[CHARACTER_NAME]);
    $player_character_weapon_id = WeaponIOHelper::addWeaponToPlayerCharacter($pdo, $player_character_weapon, $errors);
    if (count($errors) > 0) {
        die(json_encode($errors));
    }
} else if ($skill_catalog_id == MARTIAL_ARTS) {
    $player_character_weapon = WeaponSkillHelper::buildMartialArtsWeapon($input[PLAYER_NAME], $input[CHARACTER_NAME]);
    $player_character_weapon_id = WeaponIOHelper::addWeaponToPlayerCharacter($pdo, $player_character_weapon, $errors);
    if (count($errors) > 0) {
        die(json_encode($errors));
    }
}

if (!empty($player_character_weapon_id)) {
    $player_character_weapon_id = $player_character_weapon_id[PLAYER_CHARACTER_WEAPON_ID];
}
error_log("After Weapon ID: " . print_r($player_character_weapon_id, true));

// Non 'Martial Weapon' skill
if ($player_character_weapon_id == OPTIONAL_INTEGER_PARAMETER) {
    error_log("Calling addSkillToPlayerCharacter");
    $player_character_skill_id = addSkillToPlayerCharacter($pdo, $input, $errors);
} else {
    error_log("Calling addSkillMartialWeaponToPlayerCharacter");
    $player_character_skill_id = addSkillMartialWeaponToPlayerCharacter($pdo, $input, $player_character_weapon_id, $errors);
}

RestHeaderHelper::emitRestHeaders();
if(count($errors) > 0) {
    echo json_encode($errors);
} else {
    $log[] = "SUCCESS|";
    $log[] = "Character Skill Add|";

    echo json_encode($log);
}

function addSkillToPlayerCharacter(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL addSkill(:playerName, :characterName, :skillCatalogId, :playerSkillName, :isSkillFocus, :weaponProficiencyId, :weapon2ProficiencyId, :weaponSpecializationTypeId)";

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

    $weapon_specialization_type_id = WeaponSpecializationType::None->value;
    if ($input[WEAPON_SPECIALIZATION_TYPE_ID] != OPTIONAL_INTEGER_PARAMETER) {
        $weapon_specialization_type_id = $input[WEAPON_SPECIALIZATION_TYPE_ID];
    }
    
    $statement->bindParam(':weaponSpecializationTypeId', $weapon_specialization_type_id, PDO::PARAM_INT);
    
    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addSkillToPlayerCharacter : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function addSkillMartialWeaponToPlayerCharacter($pdo, $input, $player_character_weapon_id, &$errors) {
	$sql_exec = "CALL addSkillMartialWeaponToPlayerCharacter(:playerName, :characterName, :skillCatalogId, :weaponProficiencyId, :playerCharacterWeaponId)";

    $statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);
    $statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);
    $statement->bindParam(':skillCatalogId', $input[SKILL_CATALOG_ID], PDO::PARAM_INT);
    $statement->bindParam(':weaponProficiencyId', $input[WEAPON_PROFICIENCY_ID], PDO::PARAM_INT);
    $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addSkillMartialWeaponToPlayerCharacter : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}
