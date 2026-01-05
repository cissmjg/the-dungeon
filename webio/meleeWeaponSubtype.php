<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_WEAPON_SUBTYPE = 'meleeWeaponSubtype';

function getMeleeWeaponSubtype(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, MELEE_WEAPON_SUBTYPE, OPTIONAL_INTEGER_PARAMETER);
}