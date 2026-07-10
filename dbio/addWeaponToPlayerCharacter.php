<?php

require_once __DIR__ . '/../env.php';
require_once __DIR__ . '/../validateCredentials.php';
$pdo = require_once __DIR__ . '/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/../helper/RestHeaderHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
require_once __DIR__ . '/../helper/CurlHelper.php';
require_once __DIR__ . '/../webio/characterAction.php';
require_once __DIR__ . '/../characterActionRoutes.php';
require_once __DIR__ . '/../helper/WeaponIOHelper.php';

require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/weaponProficiencyId.php';
require_once __DIR__ . '/../webio/weaponDescription.php';
require_once __DIR__ . '/../webio/weaponLocation.php';
require_once __DIR__ . '/../webio/isProficient.php';
require_once __DIR__ . '/../webio/isReady.php';
require_once __DIR__ . '/../webio/craftStatus.php';
require_once __DIR__ . '/../webio/strengthBonusAvailable.php';
require_once __DIR__ . '/../webio/playerNote1.php';
require_once __DIR__ . '/../webio/playerNote2.php';
require_once __DIR__ . '/../webio/playerNote3.php';
require_once __DIR__ . '/../webio/mastercraftHitDescription.php';
require_once __DIR__ . '/../webio/mastercraftDamageDescription.php';
require_once __DIR__ . '/../webio/meleeWeaponType.php';
require_once __DIR__ . '/../webio/meleeWeaponSubtype.php';
require_once __DIR__ . '/../webio/meleeWeaponSpeed.php';
require_once __DIR__ . '/../webio/meleeWeaponDamage.php';
require_once __DIR__ . '/../webio/meleeAttacksPerRound.php';
require_once __DIR__ . '/../webio/meleeNumberOfHands.php';
require_once __DIR__ . '/../webio/meleeAdditionalText.php';
require_once __DIR__ . '/../webio/meleeHitBonus.php';
require_once __DIR__ . '/../webio/meleeDamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec1Description.php';
require_once __DIR__ . '/../webio/meleeSpec2HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec2DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec2Description.php';
require_once __DIR__ . '/../webio/meleeSpec3HitBonus.php';
require_once __DIR__ . '/../webio/meleeSpec3DamageBonus.php';
require_once __DIR__ . '/../webio/meleeSpec3Description.php';
require_once __DIR__ . '/../webio/missileWeaponType.php';
require_once __DIR__ . '/../webio/missileWeaponSubtype.php';
require_once __DIR__ . '/../webio/missileWeaponSpeed.php';
require_once __DIR__ . '/../webio/missileWeaponDamage.php';
require_once __DIR__ . '/../webio/missileAttacksPerRound.php';
require_once __DIR__ . '/../webio/missileAdditionalText.php';
require_once __DIR__ . '/../webio/missileSpec1HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec1DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec1Description.php';
require_once __DIR__ . '/../webio/missileSpec2HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec2DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec2Description.php';
require_once __DIR__ . '/../webio/missileSpec3HitBonus.php';
require_once __DIR__ . '/../webio/missileSpec3DamageBonus.php';
require_once __DIR__ . '/../webio/missileSpec3Description.php';
require_once __DIR__ . '/../webio/missileShortRange.php';
require_once __DIR__ . '/../webio/missileMediumRange.php';
require_once __DIR__ . '/../webio/missileLongRange.php';
require_once __DIR__ . '/../webio/missileHitBonus.php';
require_once __DIR__ . '/../webio/missileDamageBonus.php';

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

$player_character_weapon_skill_id = WeaponIOHelper::addWeaponToPlayerCharacter($pdo, $input, $errors);
if (count($errors) > 0) {
    RestHeaderHelper::emitRestHeaders();
	echo json_encode($errors);
    exit;
}

$url = CurlHelper::buildCharacterActionRouterUrl();
$url = CurlHelper::addParameter($url, CHARACTER_ACTION, paramValue: CHARACTER_ACTION_EDIT_PLAYER_CHARACTER_WEAPONS);
$url = CurlHelper::addParameter($url, PLAYER_NAME, paramValue: $input[PLAYER_NAME]);
$url = CurlHelper::addParameter($url, CHARACTER_NAME, $input[CHARACTER_NAME]);

$location_header = CurlHelper::buildLocationHeader($url);
header($location_header);
