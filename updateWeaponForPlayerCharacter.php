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
require_once 'playerCharacterWeaponId.php';
require_once __DIR__ . '/webio/weaponDescription.php';
require_once __DIR__ . '/webio/weaponLocation.php';
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
require_once 'meleeSpec3Description.php';
require_once 'missileWeaponType.php';
require_once 'missileWeaponSpeed.php';
require_once 'missileWeaponDamage.php';
require_once 'missileAttacksPerRound.php';
require_once 'missileAdditionalText.php';
require_once 'missileSpec1HitBonus.php';
require_once 'missileSpec1DamageBonus.php';
require_once 'missileSpec1Description.php';
require_once 'missileSpec2HitBonus.php';
require_once 'missileSpec2DamageBonus.php';
require_once 'missileSpec2Description.php';
require_once 'missileSpec3HitBonus.php';
require_once 'missileSpec3DamageBonus.php';
require_once 'missileSpec3Description.php';
require_once 'missileShortRange.php';
require_once 'missileMediumRange.php';
require_once 'missileLongRange.php';
require_once 'missileHitBonus.php';
require_once 'missileDamageBonus.php';

$input = [];
$log = [];
$errors = [];

// Filter and sanitize weapon related fields
getPlayerName($errors, $input);
getCharacterName($errors, $input);
getPlayerCharacterWeaponId($errors, $input);
getWeaponDescription($errors, $input);
getWeaponLocation($errors, $input, OPTIONAL_STRING_PARAMETER);
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
updateWeaponForPlayerCharacter($pdo, $input, $errors, $log);
if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();

    foreach ($log as $log_text) {
        $errors[] = $log_text;
    }
	echo json_encode($errors);
    exit;
}

$url = CurlHelper::buildCharacterActionRouterUrl($input[PLAYER_NAME], 'playerCharacterWeaponMain');
$url = CurlHelper::addParameter($url, CHARACTER_NAME, $input[CHARACTER_NAME]);

$location_header = 'Location:' . $url;
header($location_header);

function updateWeaponForPlayerCharacter(\PDO $pdo, $input, &$errors, &$log) {
	$sql_exec = "CALL updateWeaponForPlayerCharacter(:weaponId, :weaponDescription, :weaponLocation, :isReady, :isPreferred, :craftStatus, :strengthBonusAvailable, :playerNote1, :playerNote2, :playerNote3, :mastercraftHitDescription, :mastercraftDamageDescription, :meleeWeaponSpeed, :meleeWeaponDamage, :meleeAttacksPerRound, :meleeNumberOfHands, :meleeAdditionalText, :meleeHitBonus, :meleeDamageBonus, :meleeSpec1HitBonus, :meleeSpec1DamageBonus, :meleeSpec1Description, :meleeSpec2HitBonus, :meleeSpec2DamageBonus, :meleeSpec2Description, :meleeSpec3HitBonus, :meleeSpec3DamageBonus, :meleeSpec3Description, :missileWeaponSpeed, :missileWeaponDamage, :missileAttacksPerRound, :missileAdditionalText, :missileHitBonus, :missileDamageBonus, :missileSpec1HitBonus, :missileSpec1DamageBonus, :missileSpec1Description, :missileSpec2HitBonus, :missileSpec2DamageBonus, :missileSpec2Description, :missileSpec3HitBonus, :missileSpec3DamageBonus, :missileSpec3Description, :missileShortRange, :missileMediumRange, :missileLongRange)";

    $null_value = NULL;
    $true_value = true;
    $false_value = false;
    $zero_value = 0;
    $optional_string_parameter = OPTIONAL_STRING_PARAMETER;

	$statement = $pdo->prepare($sql_exec);

    $statement->bindParam(':weaponId', $input['playerCharacterWeaponId'], PDO::PARAM_INT);
    $log[] = 'weaponId: ' . $input['playerCharacterWeaponId'];

    $statement->bindParam(':weaponDescription', $input[WEAPON_DESCRIPTION], PDO::PARAM_STR);
    $log[] = 'weaponDescription: ' . $input[WEAPON_DESCRIPTION];

    if ($input[WEAPON_LOCATION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':weaponLocation', $null_value, PDO::PARAM_NULL);
        $log[] = 'weaponLocation: NULL';
    }
    else {
        $statement->bindParam(':weaponLocation', $input[WEAPON_LOCATION], PDO::PARAM_STR);
        $log[] = 'weaponLocation: ' . $input[WEAPON_LOCATION];
    }

    if (strcasecmp($input[IS_READY], 'YES') == 0) {
        $statement->bindParam(':isReady', $true_value, PDO::PARAM_BOOL);
        $log[] = 'isReady: ' . $input[IS_READY];
    } else {
        $statement->bindParam(':isReady', $false_value, PDO::PARAM_BOOL);
        $log[] = 'isReady: ' . $input[IS_READY];
    }

    if (strcasecmp($input[IS_PREFERRED], 'YES') == 0) {
        $statement->bindParam(':isPreferred', $true_value, PDO::PARAM_BOOL);
        $log[] = 'isPreferred: ' . $input[IS_PREFERRED];
    } else {
        $statement->bindParam(':isPreferred', $false_value, PDO::PARAM_BOOL);
        $log[] = 'isPreferred: ' . $input[IS_PREFERRED];
    }

    $statement->bindParam(':craftStatus', $input['craftStatus'], PDO::PARAM_INT);
    $log[] = 'craftStatus: ' . $input['craftStatus'];

    if (strcasecmp($input[STRENGTH_BONUS_AVAILABLE], 'YES') == 0) {
        $statement->bindParam(':strengthBonusAvailable', $true_value, PDO::PARAM_BOOL);
        $log[] = 'strengthBonusAvailable: ' . $input[STRENGTH_BONUS_AVAILABLE];
    } else {
        $statement->bindParam(':strengthBonusAvailable', $false_value, PDO::PARAM_BOOL);
        $log[] = 'strengthBonusAvailable: ' . $input[STRENGTH_BONUS_AVAILABLE];
    }

    if ($input[PLAYER_NOTE1] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote1', $null_value, PDO::PARAM_NULL);
        $log[] = 'playerNote1: NULL';
    } else {
        $statement->bindParam(':playerNote1', $input[PLAYER_NOTE1], PDO::PARAM_STR);
        $log[] = 'playerNote1: ' . $input[PLAYER_NOTE1];
    }

    if ($input[PLAYER_NOTE2] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote2', $null_value, PDO::PARAM_NULL);
        $log[] = 'playerNote2: NULL';
    } else {
        $statement->bindParam(':playerNote2', $input[PLAYER_NOTE2], PDO::PARAM_STR);
        $log[] = 'playerNote2: ' . $input[PLAYER_NOTE2];
    }

    if ($input[PLAYER_NOTE3] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote3', $null_value, PDO::PARAM_NULL);
        $log[] = 'playerNote3: NULL';
    } else {
        $statement->bindParam(':playerNote3', $input[PLAYER_NOTE3], PDO::PARAM_STR);
        $log[] = 'playerNote3: ' . $input[PLAYER_NOTE3];
    }

    if ($input[MASTERCRAFT_HIT_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftHitDescription', $null_value, PDO::PARAM_NULL);
        $log[] = 'mastercraftHitDescription: NULL';
    } else {
        $statement->bindParam(':mastercraftHitDescription', $input[MASTERCRAFT_HIT_DESCRIPTION], PDO::PARAM_STR);
        $log[] = 'mastercraftHitDescription: ' . $input[MASTERCRAFT_HIT_DESCRIPTION];
    }

    if ($input[MASTERCRAFT_DAMAGE_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftDamageDescription', $null_value, PDO::PARAM_NULL);
        $log[] = 'mastercraftDamageDescription: NULL';
    } else {
        $statement->bindParam(':mastercraftDamageDescription', $input[MASTERCRAFT_DAMAGE_DESCRIPTION], PDO::PARAM_STR);
        $log[] = 'mastercraftDamageDescription: ' . $input[MASTERCRAFT_DAMAGE_DESCRIPTION];
    }

    if ($input[MELEE_WEAPON_TYPE] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':meleeWeaponSpeed', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'meleeWeaponSpeed: ' . $optional_string_parameter;

        $statement->bindParam(':meleeWeaponDamage', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'meleeWeaponDamage: ' . $optional_string_parameter;

        $statement->bindParam(':meleeAttacksPerRound', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'meleeAttacksPerRound: ' . $optional_string_parameter;

        $statement->bindParam(':meleeNumberOfHands', $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeNumberOfHands: NULL';

        $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeAdditionalText: NULL';

        $statement->bindParam(MELEE_HIT_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeHitBonus: NULL';

        $statement->bindParam(MELEE_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeDamageBonus: NULL';

        $statement->bindParam(MELEE_SPEC1_HIT_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec1HitBonus: NULL';

        $statement->bindParam(MELEE_SPEC1_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec1DamageBonus: NULL';

        $statement->bindParam(MELEE_SPEC1_DESCRIPTION, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec1Description: NULL';

        $statement->bindParam(MELEE_SPEC2_HIT_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec2HitBonus: NULL';

        $statement->bindParam(MELEE_SPEC2_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec2DamageBonus: NULL';

        $statement->bindParam(MELEE_SPEC2_DESCRIPTION, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec2Description: NULL';

        $statement->bindParam(MELEE_SPEC3_HIT_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec3HitBonus: NULL';

        $statement->bindParam(MELEE_SPEC3_DAMAGE_BONUS, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec3DamageBonus: NULL';

        $statement->bindParam(MELEE_SPEC3_DESCRIPTION, $null_value, PDO::PARAM_NULL);
        $log[] = 'meleeSpec3Description: NULL';
    }
    else {
        $statement->bindParam(':meleeWeaponSpeed', $input[MELEE_WEAPON_SPEED], PDO::PARAM_STR);
        $log[] = 'meleeWeaponSpeed: ' . $input[MELEE_WEAPON_SPEED];

        $statement->bindParam(':meleeWeaponDamage', $input[MELEE_WEAPON_DAMAGE], PDO::PARAM_STR);
        $log[] = 'meleeWeaponDamage: ' . $input[MELEE_WEAPON_DAMAGE];

        $statement->bindParam(':meleeAttacksPerRound', $input[MELEE_ATTACKS_PER_ROUND], PDO::PARAM_STR);
        $log[] = 'meleeWeaponDamage: ' . $input[MELEE_WEAPON_DAMAGE];

        $statement->bindParam(':meleeNumberOfHands', $input[MELEE_NUMBER_OF_HANDS], PDO::PARAM_STR);
        $log[] = 'meleeNumberOfHands: ' . $input[MELEE_NUMBER_OF_HANDS];

        if ($input[MELEE_ADDITIONAL_TEXT] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeAdditionalText: NULL';
        } else {
            $statement->bindParam(':meleeAdditionalText', $input[MELEE_ADDITIONAL_TEXT], PDO::PARAM_STR);
            $log[] = 'meleeAdditionalText: ' . $input[MELEE_ADDITIONAL_TEXT];
        }

        if ($input[MELEE_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeHitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeHitBonus: NULL';
        } else {
            $statement->bindParam(':meleeHitBonus',  $input[MELEE_HIT_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeHitBonus: ' . $input[MELEE_HIT_BONUS];
        }

        if ($input[MELEE_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeDamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeDamageBonus: NULL';
        } else {
            $statement->bindParam(':meleeDamageBonus',  $input[MELEE_DAMAGE_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeDamageBonus: ' . $input[MELEE_DAMAGE_BONUS];
        }

        if ($input[MELEE_SPEC1_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec1HitBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec1HitBonus',  $input[MELEE_SPEC1_HIT_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec1HitBonus: ' . $input[MELEE_SPEC1_HIT_BONUS];
        }

        if ($input[MELEE_SPEC1_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec1DamageBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec1DamageBonus',  $input[MELEE_SPEC1_DAMAGE_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec1DamageBonus: ' . $input[MELEE_SPEC1_DAMAGE_BONUS];
        }

        if ($input[MELEE_SPEC1_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec1Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec1Description: NULL';
        } else {
            $statement->bindParam(':meleeSpec1Description', $input[MELEE_SPEC1_DESCRIPTION], PDO::PARAM_STR);
            $log[] = 'meleeSpec1Description: ' . $input[MELEE_SPEC1_DESCRIPTION];
        }

        if ($input[MELEE_SPEC2_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec2HitBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec2HitBonus',  $input[MELEE_SPEC2_HIT_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec2HitBonus: ' . $input[MELEE_SPEC2_HIT_BONUS];
        }

        if ($input[MELEE_SPEC2_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec2DamageBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec2DamageBonus',  $input[MELEE_SPEC2_DAMAGE_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec2DamageBonus: ' . $input[MELEE_SPEC2_DAMAGE_BONUS];
        }

        if ($input[MELEE_SPEC2_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec2Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec2Description: NULL';
        } else {
            $statement->bindParam(':meleeSpec2Description', $input[MELEE_SPEC2_DESCRIPTION], PDO::PARAM_STR);
            $log[] = 'meleeSpec2Description: ' . $input[MELEE_SPEC2_DESCRIPTION];
        }

        if ($input[MELEE_SPEC3_HIT_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec3HitBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec3HitBonus',  $input[MELEE_SPEC3_HIT_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec3HitBonus: ' . $input[MELEE_SPEC3_HIT_BONUS];
        }

        if ($input[MELEE_SPEC3_DAMAGE_BONUS] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec3DamageBonus: NULL';
        } else {
            $statement->bindParam(':meleeSpec3DamageBonus',  $input[MELEE_SPEC3_DAMAGE_BONUS], PDO::PARAM_INT);
            $log[] = 'meleeSpec3DamageBonus: ' . $input[MELEE_SPEC3_DAMAGE_BONUS];
        }

        if ($input[MELEE_SPEC3_DESCRIPTION] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec3Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'meleeSpec3Description: NULL';
        } else {
            $statement->bindParam(':meleeSpec3Description', $input[MELEE_SPEC3_DESCRIPTION], PDO::PARAM_STR);
            $log[] = 'meleeSpec3Description: ' . $input[MELEE_SPEC3_DESCRIPTION];
        }
    }

    if ($input['missileWeaponType'] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':missileWeaponSpeed', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'missileWeaponSpeed: ' . $optional_string_parameter;

        $statement->bindParam(':missileWeaponDamage', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'missileWeaponDamage: ' . $optional_string_parameter;

        $statement->bindParam(':missileAttacksPerRound', $optional_string_parameter, PDO::PARAM_STR);
        $log[] = 'missileAttacksPerRound: ' . $optional_string_parameter;

        $statement->bindParam(':missileAdditionalText', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileAdditionalText: NULL';

        $statement->bindParam(':missileHitBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileHitBonus: NULL';

        $statement->bindParam(':missileDamageBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileDamageBonus: NULL';

        $statement->bindParam(':missileSpec1HitBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec1HitBonus: NULL';

        $statement->bindParam(':missileSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec1DamageBonus: NULL';

        $statement->bindParam(':missileSpec1Description', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec1Description: NULL';

        $statement->bindParam(':missileSpec2HitBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec2HitBonus: NULL';

        $statement->bindParam(':missileSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec2DamageBonus: NULL';

        $statement->bindParam(':missileSpec2Description', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec2Description: NULL';

        $statement->bindParam(':missileSpec3HitBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec3HitBonus: NULL';

        $statement->bindParam(':missileSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec3DamageBonus: NULL';

        $statement->bindParam(':missileSpec3Description', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileSpec3Description: NULL';

        $statement->bindParam(':missileShortRange', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileShortRange: NULL';

        $statement->bindParam(':missileMediumRange', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileMediumRange: NULL';

        $statement->bindParam(':missileLongRange', $null_value, PDO::PARAM_NULL);
        $log[] = 'missileLongRange: NULL';
    }
    else {
        $statement->bindParam(':missileWeaponSpeed', $input['missileWeaponSpeed'], PDO::PARAM_STR);
        $log[] = 'missileWeaponSpeed: ' . $input['missileWeaponSpeed'];

        $statement->bindParam(':missileWeaponDamage', $input['missileWeaponDamage'], PDO::PARAM_STR);
        $log[] = 'missileWeaponDamage: ' . $input['missileWeaponDamage'];

        $statement->bindParam(':missileAttacksPerRound', $input['missileAttacksPerRound'], PDO::PARAM_STR);
        $log[] = 'missileAttacksPerRound: ' . $input['missileAttacksPerRound'];

        if ($input['missileHitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileHitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileHitBonus: NULL';
        } else {
            $statement->bindParam(':missileHitBonus', $input['missileHitBonus'], PDO::PARAM_INT);
            $log[] = 'missileHitBonus: ' . $input['missileHitBonus'];
        }

        if ($input['missileDamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileDamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileDamageBonus: NULL';
        } else {
            $statement->bindParam(':missileDamageBonus', $input['missileDamageBonus'], PDO::PARAM_INT);
            $log[] = 'missileDamageBonus: ' . $input['missileDamageBonus'];
        }

        if ($input['missileSpec1HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec1HitBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec1HitBonus', $input['missileSpec1HitBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec1HitBonus: ' . $input['missileSpec1HitBonus'];
        }

        if ($input['missileSpec1DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec1DamageBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec1DamageBonus', $input['missileSpec1DamageBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec1DamageBonus: ' . $input['missileSpec1DamageBonus'];
        }

        if ($input['missileSpec1Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec1Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec1Description: NULL';
        } else {
            $statement->bindParam(':missileSpec1Description', $input['missileSpec1Description'], PDO::PARAM_STR);
            $log[] = 'missileSpec1Description: ' . $input['missileSpec1Description'];
        }

        if ($input['missileSpec2HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec2HitBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec2HitBonus', $input['missileSpec2HitBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec2HitBonus: ' . $input['missileSpec2HitBonus'];
        }

        if ($input['missileSpec2DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec2DamageBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec2DamageBonus', $input['missileSpec2DamageBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec2DamageBonus: ' . $input['missileSpec2DamageBonus'];
        }

        if ($input['missileSpec2Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec2Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec2Description: NULL';
        } else {
            $statement->bindParam(':missileSpec2Description', $input['missileSpec2Description'], PDO::PARAM_STR);
            $log[] = 'missileSpec2Description: ' . $input['missileSpec2Description'];
        }

        if ($input['missileSpec3HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3HitBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec3HitBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec3HitBonus', $input['missileSpec3HitBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec3HitBonus: ' . $input['missileSpec3HitBonus'];
        }

        if ($input['missileSpec3DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec3DamageBonus: NULL';
        } else {
            $statement->bindParam(':missileSpec3DamageBonus', $input['missileSpec3DamageBonus'], PDO::PARAM_INT);
            $log[] = 'missileSpec3DamageBonus: ' . $input['missileSpec3DamageBonus'];
        }

        if ($input['missileSpec3Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec3Description', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileSpec3Description: NULL';
        } else {
            $statement->bindParam(':missileSpec3Description', $input['missileSpec3Description'], PDO::PARAM_STR);
            $log[] = 'missileSpec3Description: ' . $input['missileSpec3Description'];
        }

        if ($input['missileShortRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileShortRange', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileShortRange: NULL';
        } else {
            $statement->bindParam(':missileShortRange', $input['missileShortRange'], PDO::PARAM_STR);
            $log[] = 'missileShortRange: ' . $input['missileShortRange'];
        }

        if ($input['missileMediumRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileMediumRange', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileMediumRange: NULL';
        } else {
            $statement->bindParam(':missileMediumRange', $input['missileMediumRange'], PDO::PARAM_STR);
            $log[] = 'missileMediumRange: ' . $input['missileMediumRange'];
        }

        if ($input['missileLongRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileLongRange', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileLongRange: NULL';
        } else {
            $statement->bindParam(':missileLongRange', $input['missileLongRange'], PDO::PARAM_STR);
            $log[] = 'missileLongRange: ' . $input['missileLongRange'];
        }

        if ($input['missileAdditionalText'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileAdditionalText', $null_value, PDO::PARAM_NULL);
            $log[] = 'missileAdditionalText: NULL';
        } else {
            $statement->bindParam(':missileAdditionalText', $input['missileAdditionalText'], PDO::PARAM_STR);
            $log[] = 'missileAdditionalText: ' . $input['missileAdditionalText'];
        }
    }

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in updateWeaponForPlayerCharacter : " . $e->getMessage();
	}
}
