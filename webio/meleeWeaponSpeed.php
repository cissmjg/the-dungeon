<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_WEAPON_SPEED = 'meleeWeaponSpeed';

function getMeleeWeaponSpeed(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_WEAPON_SPEED, OPTIONAL_STRING_PARAMETER);
}