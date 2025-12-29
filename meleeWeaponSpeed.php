<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeWeaponSpeed(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeWeaponSpeed', OPTIONAL_STRING_PARAMETER);
}