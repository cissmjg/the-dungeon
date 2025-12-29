<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeWeaponType(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'meleeWeaponType', OPTIONAL_INTEGER_PARAMETER);
}