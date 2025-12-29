<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getPlayerCharacterWeaponId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponId');
}

function getOptionalPlayerCharacterWeaponId(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponId', OPTIONAL_INTEGER_PARAMETER);
}