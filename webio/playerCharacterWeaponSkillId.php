<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const PLAYER_CHARACTER_WEAPON_SKILL_ID = 'playerCharacterWeaponSkillId';

function getPlayerCharacterWeaponSkillId(&$errors, &$input, $default_value) {
	getRequiredIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_SKILL_ID);
}

function getOptionalPlayerCharacterWeaponSkillId(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, PLAYER_CHARACTER_WEAPON_SKILL_ID, OPTIONAL_INTEGER_PARAMETER);
}