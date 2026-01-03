<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once 'optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMissileHitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileHitBonus', OPTIONAL_STRING_PARAMETER);
}