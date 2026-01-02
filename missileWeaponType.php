<?php
require_once 'requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileWeaponType(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'missileWeaponType', OPTIONAL_INTEGER_PARAMETER);
}