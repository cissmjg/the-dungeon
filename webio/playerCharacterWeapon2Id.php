<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const PLAYER_CHARACTER_WEAPON2_ID = 'playerCharacterWeapon2Id';

function getPlayerCharacterWeapon2Id(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON2_ID);
}

function getOptionalPlayerCharacterWeapon2Id(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON2_ID, OPTIONAL_INTEGER_PARAMETER);
}