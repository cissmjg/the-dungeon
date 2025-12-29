<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMissileSpec1Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec1Description', OPTIONAL_STRING_PARAMETER);
}