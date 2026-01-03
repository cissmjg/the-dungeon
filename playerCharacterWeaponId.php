<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getPlayerCharacterWeaponId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponId');
}

function getOptionalPlayerCharacterWeaponId(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponId', OPTIONAL_INTEGER_PARAMETER);
}