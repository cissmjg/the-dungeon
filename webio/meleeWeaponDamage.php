<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_WEAPON_DAMAGE = 'meleeWeaponDamage';

function getMeleeWeaponDamage(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_WEAPON_DAMAGE, OPTIONAL_STRING_PARAMETER);
}