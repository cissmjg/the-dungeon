<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_NUMBER_OF_HANDS = 'meleeNumberOfHands';

function getMeleeNumberOfHands(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_NUMBER_OF_HANDS, OPTIONAL_STRING_PARAMETER);
}