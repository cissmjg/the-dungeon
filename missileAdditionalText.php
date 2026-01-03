<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileAdditionalText', OPTIONAL_STRING_PARAMETER);
}