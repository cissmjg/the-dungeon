<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const PLAYER_CHARACTER_WEAPON_TALENT_ID = 'playerCharacterWeaponTalentId';

function getPlayerCharacterWeaponTalentId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_TALENT_ID);
}

function getOptionalPlayerCharacterWeaponTalentId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_TALENT_ID, OPTIONAL_INTEGER_PARAMETER);
}