<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileSpec2DamageBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec2DamageBonus', OPTIONAL_STRING_PARAMETER);
}