<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_WEAPON_TYPE = 'missileWeaponType';

function getMissileWeaponType(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, MISSILE_WEAPON_TYPE, OPTIONAL_INTEGER_PARAMETER);
}