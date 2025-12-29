<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeSpec2HitBonus(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeSpec2HitBonus', OPTIONAL_STRING_PARAMETER);
}