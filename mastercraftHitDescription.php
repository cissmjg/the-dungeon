<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMastercraftHitDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'mastercraftHitDescription', OPTIONAL_STRING_PARAMETER);
}