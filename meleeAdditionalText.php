<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeAdditionalText', OPTIONAL_STRING_PARAMETER);
}