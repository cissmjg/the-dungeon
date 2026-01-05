<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_NUMBER_OF_HANDS = 'meleeNumberOfHands';

function getMeleeNumberOfHands(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_NUMBER_OF_HANDS, OPTIONAL_STRING_PARAMETER);
}