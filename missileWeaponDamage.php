<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileWeaponDamage(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileWeaponDamage', OPTIONAL_STRING_PARAMETER);
}