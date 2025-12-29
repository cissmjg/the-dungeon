<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileShortRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileShortRange', OPTIONAL_STRING_PARAMETER);
}