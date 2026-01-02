<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileSpec3Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec3Description', OPTIONAL_STRING_PARAMETER);
}