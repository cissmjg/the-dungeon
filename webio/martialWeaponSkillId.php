<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

const MARTIAL_WEAPON_SKILL_ID = 'martialWeaponSkillId';

function getMartialWeaponSkillId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, MARTIAL_WEAPON_SKILL_ID);
}

function getOptionalMartialWeaponSkillId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, MARTIAL_WEAPON_SKILL_ID, OPTIONAL_INTEGER_PARAMETER);
}