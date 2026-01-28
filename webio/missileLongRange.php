<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_LONG_RANGE = 'missileLongRange';

function getMissileLongRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_LONG_RANGE, OPTIONAL_STRING_PARAMETER);
}