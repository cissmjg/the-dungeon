<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SPEC1_DESCRIPTION = 'missileSpec1Description';

function getMissileSpec1Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC1_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}