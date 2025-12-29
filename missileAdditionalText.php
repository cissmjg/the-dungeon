<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMissileAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileAdditionalText', OPTIONAL_STRING_PARAMETER);
}