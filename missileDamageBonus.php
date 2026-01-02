<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileDamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileDamageBonus', OPTIONAL_STRING_PARAMETER);
}