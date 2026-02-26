<?php
require_once __DIR__ . '/requiredParameter.php';
require_once __DIR__ . '/optionalParameter.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

const WEAPON2_PROFICIENCY_ID = 'weapon2ProficiencyId';

function getWeapon2ProficiencyId(&$errors, &$input) {
	getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON2_PROFICIENCY_ID);
}

function getOptionalWeapon2ProficiencyId(&$errors, &$input) {
	getOptionalIntegerParameter($errors, $input, __FILE__, WEAPON2_PROFICIENCY_ID, OPTIONAL_INTEGER_PARAMETER);
}