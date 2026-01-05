<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MELEE_SPEC1_DESCRIPTION = 'meleeSpec1Description';

function getMeleeSpec1Description(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MELEE_SPEC1_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}