<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeSpec1HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeSpec1HitBonus', OPTIONAL_STRING_PARAMETER);
}