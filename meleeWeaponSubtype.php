<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeWeaponSubtype(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'meleeWeaponSubtype', OPTIONAL_INTEGER_PARAMETER);
}