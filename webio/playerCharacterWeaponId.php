<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const PLAYER_CHARACTER_WEAPON_ID = 'playerCharacterWeaponId';

function getPlayerCharacterWeaponId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_ID);
}

function getOptionalPlayerCharacterWeaponId(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_ID, OPTIONAL_INTEGER_PARAMETER);
}