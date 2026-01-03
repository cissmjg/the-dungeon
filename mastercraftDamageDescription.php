<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const MASTERCRAFT_DAMAGE_DESCRIPTION = MASTERCRAFT_DAMAGE_DESCRIPTION;

function getMastercraftDamageDescription(&$errors, &$input) {
	getOptionalStringParameter($errors, $input, __FILE__, MASTERCRAFT_DAMAGE_DESCRIPTION, OPTIONAL_STRING_PARAMETER);
}