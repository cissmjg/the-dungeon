<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_LONG_RANGE = 'missileLongRange';

function getMissileLongRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_LONG_RANGE, OPTIONAL_STRING_PARAMETER);
}