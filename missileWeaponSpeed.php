<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileWeaponSpeed(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileWeaponSpeed', OPTIONAL_STRING_PARAMETER);
}