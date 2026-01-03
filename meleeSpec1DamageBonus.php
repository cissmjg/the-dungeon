<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeSpec1DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeSpec1DamageBonus', OPTIONAL_STRING_PARAMETER);
}