<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';
const MELEE_SPEC3_DESCRIPTION = 'meleeSpec3Description';

function getMeleeSpec3Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC3_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}