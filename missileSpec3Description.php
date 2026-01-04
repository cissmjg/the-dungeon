<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SPEC3_DESCRIPTION = MISSILE_SPEC3_DESCRIPTION;

function getMissileSpec3Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC3_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}