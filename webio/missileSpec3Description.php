<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MISSILE_SPEC3_DESCRIPTION = 'missileSpec3Description';

function getMissileSpec3Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC3_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}