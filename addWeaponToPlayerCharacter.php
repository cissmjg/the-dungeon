<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/weaponDescription.php';
require_once __DIR__ . '/webio/weaponLocation.php';
require_once __DIR__ . '/webio/isProficient.php';
require_once __DIR__ . '/webio/isReady.php';
require_once __DIR__ . '/webio/isPreferred.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once __DIR__ . '/webio/strengthBonusAvailable.php';
require_once __DIR__ . '/webio/playerNote1.php';
require_once __DIR__ . '/webio/playerNote2.php';
require_once __DIR__ . '/webio/playerNote3.php';
require_once __DIR__ . '/webio/mastercraftHitDescription.php';
require_once __DIR__ . '/webio/mastercraftDamageDescription.php';
require_once __DIR__ . '/webio/meleeWeaponType.php';
require_once __DIR__ . '/webio/meleeWeaponSubtype.php';
require_once __DIR__ . '/webio/meleeWeaponSpeed.php';
require_once __DIR__ . '/webio/meleeWeaponDamage.php';
require_once __DIR__ . '/webio/meleeAttacksPerRound.php';
require_once __DIR__ . '/webio/meleeNumberOfHands.php';
require_once __DIR__ . '/webio/meleeAdditionalText.php';
require_once __DIR__ . '/webio/meleeHitBonus.php';
require_once __DIR__ . '/webio/meleeDamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec1HitBonus.php';
require_once __DIR__ . '/webio/meleeSpec1DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec1Description.php';
require_once __DIR__ . '/webio/meleeSpec2HitBonus.php';
require_once __DIR__ . '/webio/meleeSpec2DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec2Description.php';
require_once __DIR__ . '/webio/meleeSpec3HitBonus.php';
require_once __DIR__ . '/webio/meleeSpec3DamageBonus.php';
require_once __DIR__ . '/webio/meleeSpec3Description.php';
require_once __DIR__ . '/webio/missileWeaponType.php';
require_once __DIR__ . '/webio/missileWeaponSubtype.php';
require_once __DIR__ . '/webio/missileWeaponSpeed.php';
require_once __DIR__ . '/webio/missileWeaponDamage.php';
require_once __DIR__ . '/webio/missileAttacksPerRound.php';
require_once __DIR__ . '/webio/missileAdditionalText.php';
require_once __DIR__ . '/webio/missileSpec1HitBonus.php';
require_once __DIR__ . '/webio/missileSpec1DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec1Description.php';
require_once __DIR__ . '/webio/missileSpec2HitBonus.php';
require_once __DIR__ . '/webio/missileSpec2DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec2Description.php';
require_once __DIR__ . '/webio/missileSpec3HitBonus.php';
require_once __DIR__ . '/webio/missileSpec3DamageBonus.php';
require_once __DIR__ . '/webio/missileSpec3Description.php';
require_once __DIR__ . '/webio/missileShortRange.php';
require_once __DIR__ . '/webio/missileMediumRange.php';
require_once __DIR__ . '/webio/missileLongRange.php';
require_once 'missileHitBonus.php';
require_once 'missileDamageBonus.php';

$input = [];
$log = [];
$errors = [];

// Filter and sanitize weapon related fields
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getWeaponProficiencyId($errors, $input);
getWeaponDescription($errors, $input);
getWeaponLocation($errors, $input, OPTIONAL_STRING_PARAMETER);
getIsProficient($errors, $input);
getIsReady($errors, $input);
getIsPreferred($errors, $input);
getCraftStatus($errors, $input);
getStrengthBonusAvailable($errors, $input);
getPlayerNote1($errors, $input, OPTIONAL_STRING_PARAMETER);
getPlayerNote2($errors, $input, OPTIONAL_STRING_PARAMETER);
getPlayerNote3($errors, $input, OPTIONAL_STRING_PARAMETER);
getMastercraftHitDescription($errors, $input);
getMastercraftDamageDescription($errors, $input);
getMeleeWeaponType($errors, $input);
getMeleeWeaponSubtype($errors, $input);
getMeleeWeaponSpeed($errors, $input);
getMeleeWeaponDamage($errors, $input);
getMeleeAttacksPerRound($errors, $input);
getMeleeNumberOfHands($errors, $input);
getMeleeAdditionalText($errors, $input);
getMeleeHitBonus($errors, $input);
getMeleeDamageBonus($errors, $input);
getMeleeSpec1HitBonus($errors, $input);
getMeleeSpec1DamageBonus($errors, $input);
getMeleeSpec1Description($errors, $input);
getMeleeSpec2HitBonus($errors, $input);
getMeleeSpec2DamageBonus($errors, $input);
getMeleeSpec2Description($errors, $input);
getMeleeSpec3HitBonus($errors, $input);
getMeleeSpec3DamageBonus($errors, $input);
getMeleeSpec3Description($errors, $input);
getMissileWeaponType($errors, $input);
getMissileWeaponSubtype($errors, $input);
getMissileWeaponSpeed($errors, $input);
getMissileWeaponDamage($errors, $input);
getMissileAttacksPerRound($errors, $input);
getMissileAdditionalText($errors, $input);
getMissileHitBonus($errors, $input);
getMissileDamageBonus($errors, $input);
getMissileSpec1HitBonus($errors, $input);
getMissileSpec1DamageBonus($errors, $input);
getMissileSpec1Description($errors, $input);
getMissileSpec2HitBonus($errors, $input);
getMissileSpec2DamageBonus($errors, $input);
getMissileSpec2Description($errors, $input);
getMissileSpec3HitBonus($errors, $input);
getMissileSpec3DamageBonus($errors, $input);
getMissileSpec3Description($errors, $input);
getMissileShortRange($errors, $input);
getMissileMediumRange($errors, $input);
getMissileLongRange($errors, $input);

if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
	echo json_encode($errors);
    exit;
}

$player_character_weapon_skill_id = addWeaponToPlayerCharacter($pdo, $input, $errors);
if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
	echo json_encode($errors);
    exit;
}

$url = CurlHelper::buildCharacterActionRouterUrl($input[PLAYER_NAME], 'playerCharacterWeaponMain');
$url = CurlHelper::addParameter($url, CHARACTER_NAME, $input[CHARACTER_NAME]);

$location_header = 'Location:' . $url;
header($location_header);

function addWeaponToPlayerCharacter(\PDO $pdo, $input, &$errors) {
	$sql_exec = "CALL addWeaponToPlayerCharacter(:playerName, :characterName, :weaponProficiencyId, :weaponDescription, :weaponLocation, :isProficient, :isReady, :isPreferred, :craftStatus, :strengthBonusAvailable, :playerNote1, :playerNote2, :playerNote3, :mastercraftHitDescription, :mastercraftDamageDescription, :meleeWeaponType, :meleeWeaponSubtype, :meleeWeaponSpeed, :meleeWeaponDamage, :meleeAttacksPerRound, :meleeNumberOfHands, :meleeAdditionalText, :meleeHitBonus, :meleeDamageBonus, :meleeSpec1HitBonus, :meleeSpec1DamageBonus, :meleeSpec1Description, :meleeSpec2HitBonus, :meleeSpec2DamageBonus, :meleeSpec2Description, :meleeSpec3HitBonus, :meleeSpec3DamageBonus, :meleeSpec3Description, :missileWeaponType, :missileWeaponSubtype, :missileWeaponSpeed, :missileWeaponDamage, :missileAttacksPerRound, :missileAdditionalText, :missileHitBonus, :missileDamageBonus, :missileSpec1HitBonus, :missileSpec1DamageBonus, :missileSpec1Description, :missileSpec2HitBonus, :missileSpec2DamageBonus, :missileSpec2Description, :missileSpec3HitBonus, :missileSpec3DamageBonus, :missileSpec3Description, :missileShortRange, :missileMediumRange, :missileLongRange)";

    $null_value = NULL;
    $true_value = true;
    $false_value = false;
    $zero_value = 0;
    $optional_string_parameter = OPTIONAL_STRING_PARAMETER;

	$statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':playerName', $input[PLAYER_NAME], PDO::PARAM_STR);

    $statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);

    $statement->bindParam(':weaponProficiencyId', $input[WEAPON_PROFICIENCY_ID], PDO::PARAM_INT);

    $statement->bindParam(':weaponDescription', $input[WEAPON_DESCRIPTION], PDO::PARAM_STR);

    if ($input[WEAPON_LOCATION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':weaponLocation', $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':weaponLocation', $input[WEAPON_LOCATION], PDO::PARAM_STR);
    }

    if (strcasecmp($input[IS_PROFICIENT], 'YES') == 0) {
        $statement->bindParam(':isProficient', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isProficient', $false_value, PDO::PARAM_BOOL);
    }

    if (strcasecmp($input[IS_READY], 'YES') == 0) {
        $statement->bindParam(':isReady', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isReady', $false_value, PDO::PARAM_BOOL);
    }

    if (strcasecmp($input[IS_PREFERRED], 'YES') == 0) {
        $statement->bindParam(':isPreferred', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isPreferred', $false_value, PDO::PARAM_BOOL);
    }

    $statement->bindParam(':craftStatus', $input['craftStatus'], PDO::PARAM_INT);

    if (strcasecmp($input[STRENGTH_BONUS_AVAILABLE], 'YES') == 0) {
        $statement->bindParam(':strengthBonusAvailable', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':strengthBonusAvailable', $false_value, PDO::PARAM_BOOL);
    }

    if ($input[PLAYER_NOTE1] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote1', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote1', $input[PLAYER_NOTE1], PDO::PARAM_STR);
    }

    if ($input[PLAYER_NOTE2] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote2', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote2', $input[PLAYER_NOTE2], PDO::PARAM_STR);
    }

    if ($input[PLAYER_NOTE3] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote3', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote3', $input[PLAYER_NOTE3], PDO::PARAM_STR);
    }

    if ($input[MASTERCRAFT_HIT_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftHitDescription', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':mastercraftHitDescription', $input[MASTERCRAFT_HIT_DESCRIPTION], PDO::PARAM_STR);
    }

    if ($input[MASTERCRAFT_DAMAGE_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftDamageDescription', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':mastercraftDamageDescription', $input[MASTERCRAFT_DAMAGE_DESCRIPTION], PDO::PARAM_STR);
    }

    if ($input[MELEE_WEAPON_TYPE] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':meleeWeaponType', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSubtype', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSpeed', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeWeaponDamage', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeAttacksPerRound', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeNumberOfHands', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_HIT_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC1_HIT_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC1_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC1_DESCRIPTION, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC2_HIT_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC2_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC2_DESCRIPTION, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC3_HIT_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC3_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);

        $statement->bindParam(MELEE_SPEC3_DESCRIPTION, $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':meleeWeaponType', $input[MELEE_WEAPON_TYPE], PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSubtype', $input[MELEE_WEAPON_SUBTYPE], PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSpeed', $input[MELEE_WEAPON_SPEED], PDO::PARAM_STR);

        $statement->bindParam(':meleeWeaponDamage', $input[MELEE_WEAPON_DAMAGE], PDO::PARAM_STR);

        $statement->bindParam(':meleeAttacksPerRound', $input[MELEE_ATTACKS_PER_ROUND], PDO::PARAM_STR);

        $statement->bindParam(':meleeNumberOfHands', $input[MELEE_NUMBER_OF_HANDS], PDO::PARAM_STR);
        if ($input[MELEE_ADDITIONAL_TEXT] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeAdditionalText', $input[MELEE_ADDITIONAL_TEXT], PDO::PARAM_STR);
        }

        if ($input[MELEE_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeHitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeHitBonus',  $input[MELEE_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeDamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeDamageBonus',  $input[MELEE_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC1_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1HitBonus',  $input[MELEE_SPEC1_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC1_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1DamageBonus',  $input[MELEE_SPEC1_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC1_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec1Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1Description', $input[MELEE_SPEC1_DESCRIPTION], PDO::PARAM_STR);
        }

        if ($input[MELEE_SPEC2_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2HitBonus',  $input[MELEE_SPEC2_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC2_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2DamageBonus',  $input[MELEE_SPEC2_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC2_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec2Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2Description', $input[MELEE_SPEC2_DESCRIPTION], PDO::PARAM_STR);
        }

        if ($input[MELEE_SPEC3_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3HitBonus',  $input[MELEE_SPEC3_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC3_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3DamageBonus',  $input[MELEE_SPEC3_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MELEE_SPEC3_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec3Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3Description', $input[MELEE_SPEC3_DESCRIPTION], PDO::PARAM_STR);
        }
    }

    if ($input[MISSILE_WEAPON_TYPE] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':missileWeaponType', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':missileWeaponSubtype', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':missileWeaponSpeed', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':missileWeaponDamage', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':missileAttacksPerRound', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':missileAdditionalText', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileHitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileDamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec1HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec1DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec1Description', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec2HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec2DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec2Description', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec3HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec3DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileSpec3Description', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileShortRange', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileMediumRange', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':missileLongRange', $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':missileWeaponType', $input[MISSILE_WEAPON_TYPE], PDO::PARAM_INT);
        $statement->bindParam(':missileWeaponSubtype', $input[MISSILE_WEAPON_SUBTYPE], PDO::PARAM_INT);
        $statement->bindParam(':missileWeaponSpeed', $input[MISSILE_WEAPON_SPEED], PDO::PARAM_STR);
        $statement->bindParam(':missileWeaponDamage', $input[MISSILE_WEAPON_DAMAGE], PDO::PARAM_STR);
        $statement->bindParam(':missileAttacksPerRound', $input[MISSILE_ATTACKS_PER_ROUND], PDO::PARAM_STR);

        if ($input[MISSILE_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileHitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileHitBonus', $input[MISSILE_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input['missileDamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileDamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileDamageBonus', $input['missileDamageBonus'], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC1_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1HitBonus', $input[MISSILE_SPEC1_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC1_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1DamageBonus', $input[MISSILE_SPEC1_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC1_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec1Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1Description', $input[MISSILE_SPEC1_DESCRIPTION], PDO::PARAM_STR);
        }

        if ($input[MISSILE_SPEC2_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2HitBonus', $input[MISSILE_SPEC2_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC2_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2DamageBonus', $input[MISSILE_SPEC2_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC2_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec2Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2Description', $input[MISSILE_SPEC2_DESCRIPTION], PDO::PARAM_STR);
        }

        if ($input[MISSILE_SPEC3_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3HitBonus', $input[MISSILE_SPEC3_HIT_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC3_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3DamageBonus', $input[MISSILE_SPEC3_DAMAGE_BONUS], PDO::PARAM_INT);
        }

        if ($input[MISSILE_SPEC3_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec3Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3Description', $input[MISSILE_SPEC3_DESCRIPTION], PDO::PARAM_STR);
        }

        if ($input[MISSILE_SHORT_RANGE] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileShortRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileShortRange', $input[MISSILE_SHORT_RANGE], PDO::PARAM_STR);
        }

        if ($input[MISSILE_MEDIUM_RANGE] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileMediumRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileMediumRange', $input[MISSILE_MEDIUM_RANGE], PDO::PARAM_STR);
        }

        if ($input[MISSILE_LONG_RANGE] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileLongRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileLongRange', $input[MISSILE_LONG_RANGE], PDO::PARAM_STR);
        }

        if ($input[MISSILE_ADDITIONAL_TEXT] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileAdditionalText', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileAdditionalText', $input[MISSILE_ADDITIONAL_TEXT], PDO::PARAM_STR);
        }
    }

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addWeaponToPlayerCharacter : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}
