<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeSpec1Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeSpec1Description', OPTIONAL_STRING_PARAMETER);
}