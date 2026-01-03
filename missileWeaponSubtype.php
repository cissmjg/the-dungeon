<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileWeaponSubtype(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'missileWeaponSubtype', OPTIONAL_INTEGER_PARAMETER);
}