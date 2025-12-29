<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileWeaponType(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'missileWeaponType', OPTIONAL_INTEGER_PARAMETER);
}