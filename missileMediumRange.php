<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileMediumRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileMediumRange', OPTIONAL_STRING_PARAMETER);
}