<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMastercraftHitDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'mastercraftHitDescription', OPTIONAL_STRING_PARAMETER);
}