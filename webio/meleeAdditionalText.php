<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_ADDITIONAL_TEXT = 'meleeAdditionalText';

function getMeleeAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_ADDITIONAL_TEXT, OPTIONAL_STRING_PARAMETER);
}