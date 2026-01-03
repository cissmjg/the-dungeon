<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeSpec3DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeSpec3DamageBonus', OPTIONAL_STRING_PARAMETER);
}