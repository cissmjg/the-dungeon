<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeDamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeDamageBonus', OPTIONAL_STRING_PARAMETER);
}