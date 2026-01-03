<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getMeleeNumberOfHands(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeNumberOfHands', OPTIONAL_STRING_PARAMETER);
}