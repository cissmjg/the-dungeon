<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SHORT_RANGE = 'missileShortRange';

function getMissileShortRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SHORT_RANGE, OPTIONAL_STRING_PARAMETER);
}