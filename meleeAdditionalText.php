<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeAdditionalText', OPTIONAL_STRING_PARAMETER);
}