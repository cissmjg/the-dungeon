<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeHitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeHitBonus', OPTIONAL_STRING_PARAMETER);
}