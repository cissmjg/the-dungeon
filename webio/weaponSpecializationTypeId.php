<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

const WEAPON_SPECIALIZATION_TYPE_ID = 'weaponSpecializationTypeId';

function getWeaponSpecializationTypeId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_SPECIALIZATION_TYPE_ID);
}

function getOptionalWeaponSpecializationTypeId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, WEAPON_SPECIALIZATION_TYPE_ID, OPTIONAL_INTEGER_PARAMETER);
}
?>