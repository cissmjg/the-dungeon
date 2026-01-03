<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMastercraftDamageDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'mastercraftDamageDescription', OPTIONAL_STRING_PARAMETER);
}