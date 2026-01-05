<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_ADDITIONAL_TEXT = 'missileAdditionalText';

function getMissileAdditionalText(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_ADDITIONAL_TEXT, OPTIONAL_STRING_PARAMETER);
}