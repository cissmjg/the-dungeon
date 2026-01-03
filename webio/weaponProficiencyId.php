<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

const WEAPON_PROFICIENCY_ID = 'weaponProficiencyId';

function getWeaponProficiencyId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID);
}

function getOptionalWeaponProficiencyId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID, OPTIONAL_INTEGER_PARAMETER);
}