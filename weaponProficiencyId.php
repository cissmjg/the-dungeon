<?php
require_once 'requiredParameter.php';
require_once 'optionalParameter.php';
require_once 'WebParameterHelper.php';

function getWeaponProficiencyId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'weaponProficiencyId');
}

function getOptionalWeaponProficiencyId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'weaponProficiencyId', OPTIONAL_INTEGER_PARAMETER);
}