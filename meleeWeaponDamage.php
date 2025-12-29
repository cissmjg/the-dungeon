<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeWeaponDamage(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeWeaponDamage', OPTIONAL_STRING_PARAMETER);
}