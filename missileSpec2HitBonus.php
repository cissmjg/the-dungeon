<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileSpec2HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileSpec2HitBonus', OPTIONAL_STRING_PARAMETER);
}