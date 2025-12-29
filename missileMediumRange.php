<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileMediumRange(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileMediumRange', OPTIONAL_STRING_PARAMETER);
}