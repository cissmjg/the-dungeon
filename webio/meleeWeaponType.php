<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_WEAPON_TYPE = 'meleeWeaponType';

function getMeleeWeaponType(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, MELEE_WEAPON_TYPE, OPTIONAL_INTEGER_PARAMETER);
}