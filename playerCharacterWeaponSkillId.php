<?php
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';

function getPlayerCharacterWeaponSkillId(&$errors, &$input, $default_value) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponSkillId');
}

function getOptionalPlayerCharacterWeaponSkillId(&$errors, &$input, $default_value) {
	getOptionalIntegerParameter($errors, $input, __FILE__, 'playerCharacterWeaponSkillId', OPTIONAL_INTEGER_PARAMETER);
}