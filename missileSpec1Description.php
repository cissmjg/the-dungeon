<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileSpec1Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec1Description', OPTIONAL_STRING_PARAMETER);
}