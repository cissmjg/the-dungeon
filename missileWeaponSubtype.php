<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMissileWeaponSubtype(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'missileWeaponSubtype', OPTIONAL_INTEGER_PARAMETER);
}