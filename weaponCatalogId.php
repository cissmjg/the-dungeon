<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getWeaponCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'weaponCatalogId');
}

function getOptionalWeaponCatalogId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'weaponCatalogId', OPTIONAL_INTEGER_PARAMETER);
}