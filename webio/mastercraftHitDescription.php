<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MASTERCRAFT_HIT_DESCRIPTION = 'mastercraftHitDescription';

function getMastercraftHitDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MASTERCRAFT_HIT_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}