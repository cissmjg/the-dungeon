<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_MEDIUM_RANGE = 'missileMediumRange';

function getMissileMediumRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_MEDIUM_RANGE, OPTIONAL_STRING_PARAMETER);
}