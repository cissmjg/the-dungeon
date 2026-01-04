<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_WEAPON_SUBTYPE = 'missileWeaponSubtype';

function getMissileWeaponSubtype(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, MISSILE_WEAPON_SUBTYPE, OPTIONAL_INTEGER_PARAMETER);
}