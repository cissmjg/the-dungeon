<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MISSILE_SPEC2_DESCRIPTION = 'missileSpec2Description';

function getMissileSpec2Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MISSILE_SPEC2_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}