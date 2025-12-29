<?php
require_once 'requiredParameter.php';
require_once 'WebParameterHelper.php';

function getMeleeNumberOfHands(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, 'meleeNumberOfHands', OPTIONAL_STRING_PARAMETER);
}