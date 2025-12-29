<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMissileHitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'missileHitBonus', OPTIONAL_STRING_PARAMETER);
}