<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
const WEAPON_CATALOG_ID = 'weaponCatalogId';

function getWeaponCatalogId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_CATALOG_ID);
}

function getOptionalWeaponCatalogId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, WEAPON_CATALOG_ID, OPTIONAL_INTEGER_PARAMETER);
}