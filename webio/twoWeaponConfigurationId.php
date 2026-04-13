<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

const TWO_WEAPON_CONFIGURATION_ID = 'playerCharacterTwoWeaponConfigId';

function getTwoWeaponConfigurationId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, TWO_WEAPON_CONFIGURATION_ID);
}

function getOptionalTwoWeaponConfigurationId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, TWO_WEAPON_CONFIGURATION_ID, OPTIONAL_INTEGER_PARAMETER);
}