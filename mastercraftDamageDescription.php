<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMastercraftDamageDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'mastercraftDamageDescription', OPTIONAL_STRING_PARAMETER);
}