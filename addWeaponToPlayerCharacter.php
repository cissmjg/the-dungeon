<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once 'characterName.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';

require_once 'playerName.php';
require_once 'characterName.php';
require_once 'weaponProficiencyId.php';
require_once 'weaponDescription.php';
require_once 'weaponLocation.php';
require_once 'isProficient.php';
require_once 'isReady.php';
require_once 'isPreferred.php';
require_once 'craftStatus.php';
require_once 'strengthBonusAvailable.php';
require_once 'playerNote1.php';
require_once 'playerNote2.php';
require_once 'playerNote3.php';
require_once 'mastercraftHitDescription.php';
require_once 'mastercraftDamageDescription.php';
require_once 'meleeWeaponType.php';
require_once 'meleeWeaponSubtype.php';
require_once 'meleeWeaponSpeed.php';
require_once 'meleeWeaponDamage.php';
require_once 'meleeAttacksPerRound.php';
require_once 'meleeNumberOfHands.php';
require_once 'meleeAdditionalText.php';
require_once 'meleeHitBonus.php';
require_once 'meleeDamageBonus.php';
require_once 'meleeSpec1HitBonus.php';
require_once 'meleeSpec1DamageBonus.php';
require_once 'meleeSpec1Description.php';
require_once 'meleeSpec2HitBonus.php';
require_once 'meleeSpec2DamageBonus.php';
require_once 'meleeSpec2Description.php';
require_once 'meleeSpec3HitBonus.php';
require_once 'meleeSpec3DamageBonus.php';
require_once 'meleeSpec3Description.php';
require_once 'missileWeaponType.php';
require_once 'missileWeaponSubtype.php';
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

$url = CurlHelper::buildCharacterActionRouterUrl($input['playerName'], 'playerCharacterWeaponMain');
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

    $statement->bindParam(':playerName', $input['playerName'], PDO::PARAM_STR);

    $statement->bindParam(':characterName', $input[CHARACTER_NAME], PDO::PARAM_STR);

    $statement->bindParam(':weaponProficiencyId', $input['weaponProficiencyId'], PDO::PARAM_INT);

    $statement->bindParam(':weaponDescription', $input['weaponDescription'], PDO::PARAM_STR);

    if ($input['weaponLocation'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':weaponLocation', $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':weaponLocation', $input['weaponLocation'], PDO::PARAM_STR);
    }

    if (strcasecmp($input['isProficient'], 'YES') == 0) {
        $statement->bindParam(':isProficient', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isProficient', $false_value, PDO::PARAM_BOOL);
    }

    if (strcasecmp($input['isReady'], 'YES') == 0) {
        $statement->bindParam(':isReady', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isReady', $false_value, PDO::PARAM_BOOL);
    }

    if (strcasecmp($input['isPreferred'], 'YES') == 0) {
        $statement->bindParam(':isPreferred', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':isPreferred', $false_value, PDO::PARAM_BOOL);
    }

    $statement->bindParam(':craftStatus', $input['craftStatus'], PDO::PARAM_INT);

    if (strcasecmp($input['strengthBonusAvailable'], 'YES') == 0) {
        $statement->bindParam(':strengthBonusAvailable', $true_value, PDO::PARAM_BOOL);
    } else {
        $statement->bindParam(':strengthBonusAvailable', $false_value, PDO::PARAM_BOOL);
    }

    if ($input['playerNote1'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote1', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote1', $input['playerNote1'], PDO::PARAM_STR);
    }

    if ($input['playerNote2'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote2', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote2', $input['playerNote2'], PDO::PARAM_STR);
    }

    if ($input['playerNote3'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':playerNote3', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':playerNote3', $input['playerNote3'], PDO::PARAM_STR);
    }

    if ($input['mastercraftHitDescription'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftHitDescription', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':mastercraftHitDescription', $input['mastercraftHitDescription'], PDO::PARAM_STR);
    }

    if ($input['mastercraftDamageDescription'] == OPTIONAL_STRING_PARAMETER) {
        $statement->bindParam(':mastercraftDamageDescription', $null_value, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':mastercraftDamageDescription', $input['mastercraftDamageDescription'], PDO::PARAM_STR);
    }

    if ($input['meleeWeaponType'] == OPTIONAL_INTEGER_PARAMETER) {
        $statement->bindParam(':meleeWeaponType', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSubtype', $zero_value, PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSpeed', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeWeaponDamage', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeAttacksPerRound', $optional_string_parameter, PDO::PARAM_STR);

        $statement->bindParam(':meleeNumberOfHands', $null_value, PDO::PARAM_NULL);

        $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeHitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeDamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec1HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec1DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec1Description', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec2HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec2DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec2Description', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec3HitBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec3DamageBonus', $null_value, PDO::PARAM_NULL);

        $statement->bindParam('meleeSpec3Description', $null_value, PDO::PARAM_NULL);
    }
    else {
        $statement->bindParam(':meleeWeaponType', $input['meleeWeaponType'], PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSubtype', $input['meleeWeaponSubtype'], PDO::PARAM_INT);

        $statement->bindParam(':meleeWeaponSpeed', $input['meleeWeaponSpeed'], PDO::PARAM_STR);

        $statement->bindParam(':meleeWeaponDamage', $input['meleeWeaponDamage'], PDO::PARAM_STR);

        $statement->bindParam(':meleeAttacksPerRound', $input['meleeAttacksPerRound'], PDO::PARAM_STR);

        $statement->bindParam(':meleeNumberOfHands', $input['meleeNumberOfHands'], PDO::PARAM_STR);
        if ($input['meleeAdditionalText'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeAdditionalText', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeAdditionalText', $input['meleeAdditionalText'], PDO::PARAM_STR);
        }

        if ($input['meleeHitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeHitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeHitBonus',  $input['meleeHitBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeDamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeDamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeDamageBonus',  $input['meleeDamageBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec1HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1HitBonus',  $input['meleeSpec1HitBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec1DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1DamageBonus',  $input['meleeSpec1DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec1Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec1Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec1Description', $input['meleeSpec1Description'], PDO::PARAM_STR);
        }

        if ($input['meleeSpec2HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2HitBonus',  $input['meleeSpec2HitBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec2DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2DamageBonus',  $input['meleeSpec2DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec2Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec2Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec2Description', $input['meleeSpec2Description'], PDO::PARAM_STR);
        }

        if ($input['meleeSpec3HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3HitBonus',  $input['meleeSpec3HitBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec3DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':meleeSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3DamageBonus',  $input['meleeSpec3DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['meleeSpec3Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':meleeSpec3Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':meleeSpec3Description', $input['meleeSpec3Description'], PDO::PARAM_STR);
        }
    }

    if ($input['missileWeaponType'] == OPTIONAL_INTEGER_PARAMETER) {
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
        $statement->bindParam(':missileWeaponType', $input['missileWeaponType'], PDO::PARAM_INT);
        $statement->bindParam(':missileWeaponSubtype', $input['missileWeaponSubtype'], PDO::PARAM_INT);
        $statement->bindParam(':missileWeaponSpeed', $input['missileWeaponSpeed'], PDO::PARAM_STR);
        $statement->bindParam(':missileWeaponDamage', $input['missileWeaponDamage'], PDO::PARAM_STR);
        $statement->bindParam(':missileAttacksPerRound', $input['missileAttacksPerRound'], PDO::PARAM_STR);

        if ($input['missileHitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileHitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileHitBonus', $input['missileHitBonus'], PDO::PARAM_INT);
        }

        if ($input['missileDamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileDamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileDamageBonus', $input['missileDamageBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec1HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1HitBonus', $input['missileSpec1HitBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec1DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec1DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1DamageBonus', $input['missileSpec1DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec1Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec1Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec1Description', $input['missileSpec1Description'], PDO::PARAM_STR);
        }

        if ($input['missileSpec2HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2HitBonus', $input['missileSpec2HitBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec2DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec2DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2DamageBonus', $input['missileSpec2DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec2Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec2Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec2Description', $input['missileSpec2Description'], PDO::PARAM_STR);
        }

        if ($input['missileSpec3HitBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3HitBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3HitBonus', $input['missileSpec3HitBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec3DamageBonus'] == OPTIONAL_INTEGER_PARAMETER) {
            $statement->bindParam(':missileSpec3DamageBonus', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3DamageBonus', $input['missileSpec3DamageBonus'], PDO::PARAM_INT);
        }

        if ($input['missileSpec3Description'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileSpec3Description', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileSpec3Description', $input['missileSpec3Description'], PDO::PARAM_STR);
        }

        if ($input['missileShortRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileShortRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileShortRange', $input['missileShortRange'], PDO::PARAM_STR);
        }

        if ($input['missileMediumRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileMediumRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileMediumRange', $input['missileMediumRange'], PDO::PARAM_STR);
        }

        if ($input['missileLongRange'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileLongRange', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileLongRange', $input['missileLongRange'], PDO::PARAM_STR);
        }

        if ($input['missileAdditionalText'] == OPTIONAL_STRING_PARAMETER) {
            $statement->bindParam(':missileAdditionalText', $null_value, PDO::PARAM_NULL);
        } else {
            $statement->bindParam(':missileAdditionalText', $input['missileAdditionalText'], PDO::PARAM_STR);
        }
    }

    try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in addWeaponToPlayerCharacter : " . $e->getMessage();
	}

    return $statement->fetch(PDO::FETCH_ASSOC);
}
