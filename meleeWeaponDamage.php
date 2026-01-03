<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeWeaponDamage(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeWeaponDamage', OPTIONAL_STRING_PARAMETER);
}