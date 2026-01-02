<?php
require_once 'requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileLongRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileLongRange', OPTIONAL_STRING_PARAMETER);
}