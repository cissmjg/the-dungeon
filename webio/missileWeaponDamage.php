<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_WEAPON_DAMAGE = 'missileWeaponDamage';

function getMissileWeaponDamage(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_WEAPON_DAMAGE, OPTIONAL_STRING_PARAMETER);
}