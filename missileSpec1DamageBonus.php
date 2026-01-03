<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileSpec1DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec1DamageBonus', OPTIONAL_STRING_PARAMETER);
}