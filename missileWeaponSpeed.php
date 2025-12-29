<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileWeaponSpeed(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileWeaponSpeed', OPTIONAL_STRING_PARAMETER);
}