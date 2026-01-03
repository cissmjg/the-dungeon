<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileShortRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileShortRange', OPTIONAL_STRING_PARAMETER);
}