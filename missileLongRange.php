<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileLongRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileLongRange', OPTIONAL_STRING_PARAMETER);
}