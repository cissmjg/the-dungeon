<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_WEAPON_SPEED = 'missileWeaponSpeed';

function getMissileWeaponSpeed(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_WEAPON_SPEED, OPTIONAL_STRING_PARAMETER);
}